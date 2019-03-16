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
                        <tbody>
                        <input type="hidden" name="id" v-model="postData.id">
                        <tr>
                            <td width="200">小程序appid</td>
                            <td><input class="form-control" type="text" v-model="postData.appid"></td>
                        </tr>
                        <tr>
                            <td>模板类型</td>
                            <td>
                                <input type="radio" id="type_1" value="1" v-model="type" @change="postData.mp_appid = ''">
                                <label for="type_1">小程序服务通知</label>
                                <input type="radio" id="type_2" value="2" v-model="type" @change="postData.mp_appid = ''">
                                <label for="type_2">公众号模板消息</label>
                            </td>
                        </tr>
                        <tr v-if="type == 2">
                            <td>公众号appid</td>
                            <td><input class="form-control" type="text" v-model="postData.mp_appid"></td>
                        </tr>
                        <tr>
                            <td>模板id</td>
                            <td><input class="form-control" type="text" v-model="postData.template_id">
                        </tr>
                        <tr>
                            <td>标题</td>
                            <td><input class="form-control" type="text" v-model="postData.title"></td>
                        </tr>
                        <tr>
                            <td>描述</td>
                            <td>
                                <textarea v-model="postData.description" class="form-control"  cols="30" rows="5"></textarea>
                            </td>
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
            id: '{:I("get.id")}',
            postData: {},
            type: ''
        },
        methods: {
            submitBtn: function () {
                var that = this;
                $.post('{:U("addEditTemp")}', that.postData, function (res) {
                    if (res.status) {
                        layer.msg('更新成功', {time: 1000}, function () {
                            window.parent.layer.closeAll()
                        })
                    }
                }, 'json')
            },
            getTemp: function () {
                var that = this;
                if (this.id) {
                    $.get('{:U("getTemp")}', {id: this.id}, function (res) {
                        if (res.status) {
                            that.postData = res.data;

                            if(that.postData.mp_appid == ''){
                                that.type = 1;
                            }else{
                                that.type = 2;
                            }
                        }
                    }, 'json');
                }
            }
        },
        mounted: function () {
            $(this.$el).removeClass('hidden');
            this.getTemp();
        }

    });
</script>
</body>
</html>
