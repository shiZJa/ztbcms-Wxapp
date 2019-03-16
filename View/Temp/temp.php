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
                            <td width="200">小程序</td>
                            <td>
                                <a class="btn btn-primary" @click="choose">选择小程序</a>
                            </td>
                        </tr>
                        <tr>
                            <td>模板类型</td>
                            <td>
                                <input type="radio" id="type_1" value="1" v-model="postData.type">
                                <label for="type_1">公众号服务通知</label>
                                <input type="radio" id="type_2" value="2" v-model="postData.type">
                                <label for="type_2">小程序模板消息</label>
                            </td>
                        </tr>
                        <tr>
                            <td>英文名</td>
                            <td><input class="form-control" type="text" v-model="postData.name">
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
    window.__app = new Vue({
        el: "#app",
        data: {
            id: '{:I("get.id")}',
            postData: {
                appinfo_id: 0,
                type: ''
            },
            app_name: '',
            index_choose: null
        },
        methods: {
            submitBtn: function () {
                var that = this;
                if(that.postData.appinfo_id == 0){
                    layer.msg('请选择小程序', {time: 1000});
                    return;
                }
                if(that.postData.type == ''){
                    layer.msg('请选择模板类型', {time: 1000});
                    return;
                }
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
                        }
                    }, 'json');
                }
            },
            choose: function(){
                var that = this;
                that.index_choose = layer.open({
                    title: '选择小程序',
                    type: 2,
                    area: ['80%', '80%'],
                    content: '{:U("choose")}'
                });
            },
            doChoose: function(id, title){
                var that = this;
                layer.close(that.index_choose);
                that.postData.appinfo_id = id;
                that.app_name = title;
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
