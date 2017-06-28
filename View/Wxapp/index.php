<?php if (!defined('CMS_VERSION')) {
    exit();
} ?>
<Admintemplate file="Common/Head"/>
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
    <Admintemplate file="Common/Nav"/>
    <h3 style="margin-bottom: 20px;">
        <span>小程序列表</span>
        <span style="float: right;">
            <a class="btn btn-primary" href="{:U('addWxapp')}">添加</a>
        </span>
    </h3>
    <div>
        <div id="app" class="table_list">
            <table width="100%">
                <thead>
                <tr class="h_a">
                    <td align="center">ID</td>
                    <td align="center">appid</td>
                    <td align="center">secret</td>
                    <td align="center">login_duration</td>
                    <td align="center">session_duration</td>
                    <td align="center">secret_key</td>
                    <td align="center">mch_id</td>
                    <td align="center">key</td>
                    <td align="center">是否默认</td>
                    <td align="center">操作</td>
                </tr>
                </thead>
                <tbody>
                <template v-for="item in lists">
                    <tr>
                        <td align="center">{{ item.id }}</td>
                        <td align="center">{{ item.appid }}</td>
                        <td align="center">{{ item.secret }}</td>
                        <td align="center">{{ item.login_duration }}</td>
                        <td align="center">{{ item.session_duration }}</td>
                        <td align="center">{{ item.secret_key }}</td>
                        <td align="center">{{ item.mch_id }}</td>
                        <td align="center">{{ item.key }}</td>
                        <td align="center">{{ item.is_default == 1 ? '是' : '否' }}</td>
                        <td align="center">
                            <a class="btn btn-info" :href="'{:U('addWxapp')}&id='+item.id">编辑</a>
                            <a class="btn btn-info" :href="'{:U('Wxapp/Open/domainList')}&appid='+item.appid">域名编辑</a>
                            <a class="btn btn-primary" :href="'{:U('Wxapp/Open/addTester')}&appid='+item.appid">添加体验者</a>
                            <a class="btn btn-primary" :href="'{:U('Wxapp/Open/commit')}&appid='+item.appid">代码提交</a>
                            <a class="btn btn-primary" :href="'{:U('Wxapp/Open/submitAudit')}&appid='+item.appid">提交审核</a>
                            <a @click="deleteBtn(item.id)" class="btn btn-danger" href="javascript:;">删除</a>
                        </td>
                    </tr>
                </template>
                </tbody>
            </table>
            <div v-if="page<page_count" class="pages" style="margin-top: 10px;text-align: center">
                <a @click="toPage(page>1?page-1:1)" href="javascript:;">上一页</a>
                <a @click="toPage(page<page_count?page+1:page_count)" href="javascript:;">下一页</a>
            </div>
        </div>
    </div>
</div>
<script src="//cdn.bootcss.com/layer/3.0.1/layer.js"></script>
<script src="//cdn.bootcss.com/vue/2.1.5/vue.min.js"></script>
<script>
    new Vue({
        el: "#app",
        data: {
            lists: [],
            page: 1,
            limit: 20,
            total: 0,
            page_count: 0
        },
        methods: {
            deleteBtn: function (id) {
                layer.confirm('是否确定删除', {}, function () {
                    $.ajax({
                        url: '{:U("deleteWxapp")}',
                        data: {id: id},
                        type: 'post',
                        dataType: 'json',
                        success: function (res) {
                            if (res.status) {
                                location.reload();
                            } else {
                                layer.msg(res.msg)
                            }
                        }
                    })
                })
            },
            toPage: function (page) {
                this.page = parseInt(page)
                this.getList()
            },
            getList: function () {
                var that = this
                $.ajax({
                    url: '{:U("index")}',
                    data: {page: this.page, limit: this.limit},
                    dataType: 'json',
                    success: function (res) {
                        console.log(res)
                        var data = res.data
                        that.lists = data.lists
                        that.page = parseInt(data.page)
                        that.limit = data.limit
                        that.total = data.total
                        that.page_count = Math.ceil(data.total / data.limit)
                    }
                })
            }
        },
        mounted: function () {
            this.getList()
        }
    });
</script>
</body>
</html>
