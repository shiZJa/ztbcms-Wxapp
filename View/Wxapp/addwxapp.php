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
            <form action="{:U('Wxapp/doSetting')}" method="post">
                <div class="table_full">
                    <table class="table_form" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th style="width: 80px;">属性</th>
                            <th style="width: 20%;" colspan="2">当前值</th>
                        </tr>
                        </thead>
                        <tbody>
                        <input type="hidden" name="id" v-model="settings.id">
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
                            <td>secret_key</td>
                            <td><input class="form-control" type="text" name="secret_key" v-model="settings.secret_key">
                            </td>
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
                            <td>login_duration</td>
                            <td><input class="form-control" type="number" name="login_duration"
                                       v-model="settings.login_duration"></td>
                            <td>登录实效 (单位 秒)</td>
                        </tr>
                        <tr>
                            <td>session_duration</td>
                            <td><input class="form-control" type="number" name="session_duration"
                                       v-model="settings.session_duration"></td>
                            <td>session实效 (单位 秒)</td>
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
                            <td>session实效 (单位 秒)</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="form-group">
                    <button class="btn btn-info">设置</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="//cdn.bootcss.com/vue/2.1.5/vue.min.js"></script>
<script>
    new Vue({
        el: "#app",
        data: {
            id: '{$id}',
            settings: {
                is_default: 0
            }
        },
        methods: {
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
