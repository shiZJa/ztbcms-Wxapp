<?php if (!defined('CMS_VERSION')) {
    exit();
} ?>
<Admintemplate file="Common/Head"/>
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
    <Admintemplate file="Common/Nav"/>
    <div>
        <div class="h_a">配置</div>
        <div id="app" class="hidden">
            <form id="form_id" action="javascript:;">
                <div class="table_full">
                    <table class="table_form" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th style="width: 30px;">属性</th>
                            <th style="width: 20%;" colspan="2">当前值</th>
                        </tr>
                        </thead>
                        <tbody>
                        <input type="hidden" name="id" v-model="settings.id">
                        <tr>
                            <td>名称</td>
                            <td><input class="form-control" type="text" name="appid" v-model="settings.nick_name"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>appid</td>
                            <td><input class="form-control" type="text" name="appid" v-model="settings.appid"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>secret</td>
                            <td><input class="form-control" type="text" name="secret" v-model="settings.secret"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>mch_id</td>
                            <td><input class="form-control" type="text" name="mch_id" v-model="settings.mch_id">
                            <td>微信支付商户号</td>
                        </tr>
                        <tr>
                            <td>key</td>
                            <td><input class="form-control" type="text" name="key" v-model="settings.key"></td>
                            <td>微信支付秘钥</td>
                        </tr>
                        <tr>
                            <td>office appid</td>
                            <td><input class="form-control" type="text" name="office_appid" v-model="settings.office_appid"></td>
                            <td>绑定微信公众号的appid</td>
                        </tr>
                        <tr>
                            <td>是否默认</td>
                            <td>
                                <select v-model="settings.is_default" style="width: 100px;" class="form-control"
                                        name="is_default" id="">
                                    <option value="1">是</option>
                                    <option value="0">否</option>
                                </select>
                            </td>
                            <td>微信支付秘钥</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="form-group">
                    <button @click="submitBtn" class="btn btn-info">保存</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="//cdn.bootcss.com/vue/2.1.5/vue.min.js"></script>
<script src="//cdn.bootcss.com/layer/3.0.1/layer.js"></script>
<script>
    new Vue({
        el: "#app",
        data: {
            id: '{$id}',
            settings: {
                is_default: 0,
                login_duration: 7200,
                session_duration: 2592000
            }
        },
        methods: {
            submitBtn: function () {
                $.post('{:U("doSetting")}', $('#form_id').serialize(), function (res) {
                    console.log(res)
                    if (res.status) {
                        layer.msg('更新成功', {}, function () {
                            window.parent.layer.closeAll()
                        })
                    }
                }, 'json')
            },
            getSettings: function () {
                var that = this;
                if (this.id) {
                    $.get('{:U("Wxapp/getSettings")}', {id: this.id}, function (res) {
                        if (res.status) {
                            that.settings = res.data;
                        }
                    }, 'json');
                }
            }
        },
        mounted: function () {
            $(this.$el).removeClass('hidden');
            this.getSettings();
        }

    });
</script>
</body>
</html>
