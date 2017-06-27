<?php if (!defined('CMS_VERSION')) {
    exit();
} ?>
<Admintemplate file="Common/Head"/>
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
    <Admintemplate file="Common/Nav"/>
    <div>
        <div class="h_a">添加小程序体验者</div>
        <div id="app">
            <div class="table_full">
                <table class="table_form" width="100%" cellspacing="0">
                    <tbody>
                    <tr>
                        <td width="200px;">添加者微信号</td>
                        <td><input class="form-control" type="text" v-model="wechatid">
                        </td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="form-group">
                <button @click="submitBtn" class="btn btn-info">添加</button>
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
            wechatid: '',
            appid: '{$appid}'
        },
        methods: {
            submitBtn: function () {
                var post_data = {
                    appid: this.appid,
                    wechatid: this.wechatid
                }
                $.ajax({
                    url: "{:U('addTester')}",
                    data: post_data,
                    type: 'post',
                    dataType: 'json',
                    success: function (res) {
                        console.log(res)
                        if (res.status) {
                            layer.msg('添加成功')
                        } else {
                            layer.msg(res.msg)
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
