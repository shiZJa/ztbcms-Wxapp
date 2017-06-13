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
                            <td><input class="form-control" type="text" name="appid" v-model="settings.appid">
                            <td></td>
                        </tr>
                        <tr>
                            <td>secret</td>
                            <td><input class="form-control" type="text" name="secret" v-model="settings.secret">
                            <td></td>
                        </tr>
                        <tr>
                            <td>mch_id</td>
                            <td><input class="form-control" type="text" name="mch_id" v-model="settings.mch_id">
                            <td>微信支付商户号</td>
                        </tr>
                        <tr>
                            <td>key</td>
                            <td><input class="form-control" type="text" name="key" v-model="settings.key">
                            <td>微信支付秘钥</td>
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
            settings: {}
        },
        methods: {
            getSettings: function () {
                var that = this;
                $.get('{:U("Wxapp/getSettings")}', {}, function (res) {
                    if (res.status) {
                        that.settings = res.data;
                    }
                }, 'json');
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
