<?php if (!defined('CMS_VERSION')) {
    exit();
} ?>
<Admintemplate file="Common/Head"/>
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
    <Admintemplate file="Common/Nav"/>
    <div>
        <div class="h_a">添加请求域名</div>
        <div id="app">
            <div class="table_full">
                <table class="table_form" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th style="width: 80px;">请求方式</th>
                        <th style="width: 20%;" colspan="2">添加值</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>requestdomain</td>
                        <td><input class="form-control" type="text" v-model="settings.requestdomain">
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>uploaddomain</td>
                        <td><input class="form-control" type="text" v-model="settings.uploaddomain">
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>wsrequestdomain</td>
                        <td><input class="form-control" type="text" v-model="settings.wsrequestdomain">
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>downloaddomain</td>
                        <td><input class="form-control" type="text" v-model="settings.downloaddomain">
                        </td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="form-group">
                <button @click="settingBtn" class="btn btn-info">设置</button>
            </div>
        </div>
    </div>
</div>
<script src="//cdn.bootcss.com/vue/2.1.5/vue.min.js"></script>
<script src="//cdn.bootcss.com/layer/3.0.1/layer.js"></script>
<script>
    new Vue({
        el: "#app",
        data: {
            appid: '{$appid}',
            settings: {}
        },
        methods: {
            settingBtn: function () {
                var that = this
                var post_data = {
                    appid: this.appid,
                    settings: this.settings
                }
                $.ajax({
                    url: '{:U("addDomain")}',
                    data: post_data,
                    type: 'post',
                    dataType: 'json',
                    success: function (res) {
                        console.log(res)
                        if (res.status) {
                            layer.msg('添加成功', {}, function () {
                                location.href = "{:U('domainList')}&appid=" + that.appid
                            })
                        } else {
                            layer.msg('域名不合法或者重复添加')
                        }
                    }
                })
            }
        },
        mounted: function () {

        }
    });
</script>
</body>
</html>
