<?php if (!defined('CMS_VERSION')) {
    exit();
} ?>
<Admintemplate file="Common/Head"/>
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
    <Admintemplate file="Common/Nav"/>
    <div>
        <div class="h_a">提交上线申请</div>
        <div id="app">
            <div class="table_full">
                <table class="table_form" width="100%" cellspacing="0">
                    <tbody>
                    <tr>
                        <td width="200px;">页面标题</td>
                        <td><input class="form-control" type="text" v-model="title">
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td width="200px;">页面地址</td>
                        <td>
                            <select name="" id="" v-model="address">
                                <option v-for="(item , key) in addressList" :value="key">
                                    {{ item }}
                                </option>
                            </select>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td width="200px;">标签</td>
                        <td><input class="form-control" type="text" v-model="tag">
                        </td>
                        <td>用空格隔开</td>
                    </tr>
                    <tr>
                        <td width="200px;">所属行业</td>
                        <td>
                            <select name="" id="" v-model="item_class">
                                <option v-for="(item , key) in categoryList" :value="key">
                                    {{ item.first_class }} - {{ item.second_class }} - {{ item.third_class }}
                                </option>
                            </select>
                        </td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="form-group">
                <button @click="submitBtn" class="btn btn-info">提交</button>
            </div>
            <div>
                提交审核状态
                <div class="table_full">
                    <table class="table_form">
                        <thead>
                        <tr>
                            <th>审核id</th>
                            <th>状态</th>
                            <th>原因</th>
                            <th>审核时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                {{ audit.auditid }}
                            </td>
                            <td>
                                {{ audit.status | statusFilters }}
                            </td>
                            <td>
                                {{ audit.reason ? audit.reason:'无' }}
                            </td>
                            <td>
                                {{ audit.create_time | getFormatTime }}
                            </td>
                            <td>
                                <a @click="releaseBtn" class="btn btn-primary" href="javascript:;">提交</a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="//cdn.bootcss.com/vue/2.1.5/vue.min.js"></script>
<script src="//cdn.bootcss.com/layer/3.0.1/layer.js"></script>
<script>
    $(document).ready(function () {
        new Vue({
            el: "#app",
            data: {
                title: '',
                item_class: 0,
                tag: '',
                address: 0,
                addressList: [],
                categoryList: [],
                audit: [],
                appid: '{$appid}'
            },
            filters: {
                statusFilters: function (value) {
                    switch (value) {
                        case '0':
                            return '审核成功';
                        case '1':
                            return '审核失败';
                        case '2':
                            return '正在审核';
                    }
                },
                getFormatTime: function (value) {
                    if(!value){
                        return '无记录';
                    }
                    var time = new Date(parseInt(value) * 1000);
                    var y = time.getFullYear();
                    var m = time.getMonth() + 1;
                    var d = time.getDate();
                    var h = time.getHours()
                    var min = time.getMinutes()
                    var res = y + '-' + (m < 10 ? '0' + m : m) + '-' + (d < 10 ? '0' + d : d) + ' ';
                    res += '  ' + (h < 10 ? '0' + h : h) + ':' + (min < 10 ? '0' + min : min)
                    return res;
                }
            },
            methods: {
                releaseBtn: function () {
                    if (this.audit.status == 2) {
                        layer.msg('是否提交审核?')
                    } else {
                        layer.alert('审核未通过')
                    }
                },
                submitBtn: function () {
                    var post_data = {
                        appid: this.appid,
                        title: this.title,
                        address: this.addressList[this.address],
                        tag: this.tag,
                        first_class: this.categoryList[this.item_class].first_class,
                        second_class: this.categoryList[this.item_class].second_class,
                        third_class: this.categoryList[this.item_class].third_class,
                    }
                    console.log(post_data)
                    $.ajax({
                        url: "{:U('submitAudit')}",
                        data: post_data,
                        dataType: 'json',
                        type: 'post',
                        success: function (res) {
                            console.log(res)
                            if (res.status) {
                                layer.msg('提交成功')
                            } else {
                                layer.msg(res.msg)
                            }
                        }
                    })
                },
                getCategoryList: function () {
                    var that = this
                    $.ajax({
                        url: '{:U("getCategoryList")}',
                        data: {appid: that.appid},
                        dataType: 'json',
                        type: 'get',
                        success: function (res) {
                            console.log(res)
                            var data = res.data
                            that.categoryList = data
                        }
                    })
                },
                getAddressList: function () {
                    var that = this
                    $.ajax({
                        url: '{:U("getAddressList")}',
                        data: {appid: that.appid},
                        dataType: 'json',
                        type: 'get',
                        success: function (res) {
                            console.log(res)
                            var data = res.data
                            that.addressList = data
                        }
                    })
                },
                getAuditstatus: function () {
                    var that = this
                    $.ajax({
                        url: '{:U("getAuditstatus")}',
                        data: {appid: that.appid},
                        dataType: 'json',
                        type: 'get',
                        success: function (res) {
                            console.log(res)
                            that.audit = res.data
                        }
                    })
                }
            },
            mounted: function () {
                this.getAddressList()
                this.getCategoryList()
                this.getAuditstatus()
            }
        });
    })
</script>
</body>
</html>
