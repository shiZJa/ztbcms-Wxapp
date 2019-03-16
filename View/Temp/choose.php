<?php if (!defined('CMS_VERSION')) {
    exit();
} ?>
<Admintemplate file="Common/Head"/>
<body class="J_scroll_fixed">
<div id="app" class="wrap J_check_wrap">
    <Admintemplate file="Common/Nav"/>
    <h3 style="margin-bottom: 20px;">
        <span>小程序列表</span>
        <span style="float: right;">
        </span>
    </h3>
    <div>
        <div class="table_list">
            <table width="100%">
                <thead>
                <tr class="h_a">
                    <td align="center">ID</td>
                    <td align="center">appid</td>
                    <td align="center">名称</td>
                    <td align="center">操作</td>
                </tr>
                </thead>
                <tbody>
                <template v-for="item in lists">
                    <tr>
                        <td align="center">{{ item.id }}</td>
                        <td align="center">{{ item.appid }}</td>
                        <td align="center">{{ item.nick_name }}</td>
                        <td align="center">
                            <a class="btn btn-info" @click="doChoose(item.id)">选择</a>
                        </td>
                    </tr>
                </template>
                </tbody>
            </table>
            <div v-if="page<page_count" class="pages" style="margin-top: 10px;text-align: center">
                <a @click="toPage(page>1?page-1:1)" href="javascript:;">上一页</a>
                <a @click="toPage(page<page_count?page+1:page_count)" href="javascript:;">下一页</a>
            </div>
        </div>
    </div>
</div>
<script src="//cdn.bootcss.com/layer/3.0.1/layer.js"></script>
<script src="//cdn.bootcss.com/vue/2.1.5/vue.min.js"></script>
<script>
    new Vue({
        el: "#app",
        data: {
            lists: [],
            page: 1,
            limit: 20,
            total: 0,
            page_count: 0
        },
        methods: {
            toPage: function (page) {
                this.page = parseInt(page)
                this.getList()
            },
            getList: function () {
                var that = this
                $.ajax({
                    url: '{:U("Wxapp/index")}',
                    data: {page: this.page, limit: this.limit},
                    dataType: 'json',
                    success: function (res) {
                        console.log(res);
                        var data = res.data;
                        that.lists = data.lists;
                        that.page = parseInt(data.page);
                        that.limit = data.limit;
                        that.total = data.total;
                        that.page_count = Math.ceil(data.total / data.limit)
                    }
                })
            },
            doChoose: function(id){
                parent.__app.doChoose(id);
            }
        },
        mounted: function () {
            this.getList()
        }
    });
</script>
</body>
</html>
