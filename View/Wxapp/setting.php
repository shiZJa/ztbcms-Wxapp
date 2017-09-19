<?php if (!defined('CMS_VERSION')) {
    exit();
} ?>
<Admintemplate file="Common/Head"/>
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
    <Admintemplate file="Common/Nav"/>
    <div>
        <div class="h_a">配置</div>
        <div>
            <form action="{:U('Wxapp/setting')}" method="post">
                <div class="table_full">
                    <table class="table_form" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th style="width: 80px;">设置项</th>
                            <th style="width: 20%;" colspan="2">当前值</th>
                        </tr>
                        </thead>
                        <tbody>
                        <input type="hidden" name="id" v-model="settings.id">
                        <tr>
                            <td>是否第三方授权</td>
                            <td>
                                <span>是 <input <if condition="$config[wxapp_is_author] eq 1 " >checked</if> name="wxapp_is_author" type="radio" value="1"></span>
                                <span style="margin-left:20px;">否 <input <if condition="$config[wxapp_is_author] eq 0 " >checked</if> name="wxapp_is_author" type="radio" value="0"></span>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>第三方代码模板id</td>
                            <td>
                                <input value="{$config[wxapp_template_id]}" class="form-control" type="number" name="wxapp_template_id"></td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="form-group">
                    <button class="btn btn-info">保存设置</button>
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

            }
        },
        mounted: function () {

        }

    });

</script>
</body>
</html>
