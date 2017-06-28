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
                        <td width="200px;">公众号appid</td>
                        <td><input class="form-control" readonly type="text" v-model="appid">
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td width="200px;">代码模板id</td>
                        <td><input class="form-control" type="text" v-model="template_id">
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td width="200px;">代码版本号</td>
                        <td><input class="form-control" type="text" v-model="user_version">
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td width="200px;">版本介绍</td>
                        <td><input class="form-control" type="text" v-model="user_desc">
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td width="200px;">自定义字段</td>
                        <td>
                            <input class="form-control" type="text" v-model="key">
                        </td>
                        <td>
                            <input class="form-control" type="text" v-model="value">
                        </td>
                        <td>
                            <a @click="addExt" class="btn btn-primary" href="javascript:;">添加</a>
                        </td>
                    </tr>
                    <tr>
                        <td width="200px;">
                            已经添加自定义字段
                        </td>
                        <td colspan="3">
                            <p v-for="item in ext">
                                {{ item.key }} = {{ item.value }}
                            </p>
                        </td>
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
<script src="//cdn.bootcss.com/vue/2.1.5/vue.js"></script>
<script src="//cdn.bootcss.com/layer/3.0.1/layer.js"></script>
<script>
    new Vue({
        el: "#app",
        data: {
            key: '',
            value: '',
            template_id: '',
            user_version: '',
            user_desc: '',
            ext: [],
            appid: '{$appid}'
        },
        methods: {
            addExt: function () {
                var ext = {
                    key: this.key,
                    value: this.value
                }
                this.ext.push(ext)
                this.key = ''
                this.value = ''
                console.log(this.ext)
            },
            submitBtn: function () {
                var that = this
                var post_data = {
                    appid: this.appid,
                    ext: this.ext,
                    template_id: this.template_id,
                    user_version: this.user_version,
                    user_desc: this.user_desc,
                }

                $.ajax({
                    url: "{:U('commit')}",
                    data: post_data,
                    dataType: 'json',
                    type: 'post',
                    success: function (res) {
                        console.log(res)
                        if (res.status) {
                            //提交成功
                            layer.msg('提交成功', {}, function () {

                            })
                            layer.open({
                                title:'体验版二维码',
                                widht: "400px",
                                height: "400px",
                                content: '<img style="width:200px;" src="{:U('getQrcode')}&appid=' + that.appid + '" />'
                            })
                        } else {
                            layer.msg('提交失败，请联系管理员')
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
