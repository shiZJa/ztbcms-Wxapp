<?php if (!defined('CMS_VERSION')) exit(); ?>
<Admintemplate file="Common/Head"/>
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
    <Admintemplate file="Common/Nav"/>
    <div >
        <div class="h_a">配置</div>
        <div id="app" class="hidden">
            <form action="{:U('Wxapp/doSetting')}" method="post">
                <div class="table_full">
                    <table class="table_form" width="100%" cellspacing="0">
                        <tbody>
                        <tr>
                            <th style="width: 80px;">属性</th>
                            <th style="width: 20%;" colspan="2">当前值</th>
                        </tr>
                        <tr v-for="setting in settings" :setting="setting">
                            <td>{{ setting.field }}</td>
                            <td><input class="form-control" type="text" :name="setting.field" v-model="setting.value"></td>
                            <td>{{ setting.comment }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="form-group">
                    <button class="btn btn-info" >设置</button>
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
            settings: []
        },
        methods: {
            getSettings: function () {
                let that = this;
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
