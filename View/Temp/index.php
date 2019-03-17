<?php if (!defined('CMS_VERSION')) {
    exit();
} ?>
<Admintemplate file="Common/Head"/>
<body class="J_scroll_fixed">
<div id="app" class="wrap J_check_wrap">
    <Admintemplate file="Common/Nav"/>
    <h3 style="margin-bottom: 20px;">
        <span>模板列表</span>
        <span style="float: right;">
            <a class="btn btn-primary" @click="openUrl('添加模板','{:U('temp')}')">添加</a>
        </span>
    </h3>
    <div>
        <div class="table_list">
            <table width="100%">
                <thead>
                <tr class="h_a">
                    <td align="center">ID</td>
                    <td align="center">英文名</td>
                    <td align="center">小程序appid</td>
                    <td align="center">模板类型</td>
                    <td align="center">模板id</td>
                    <td align="center">标题</td>
                    <td align="center">描述</td>
                    <td align="center">操作</td>
                </tr>
                </thead>
                <tbody>
                <template v-for="item in lists">
                    <tr>
                        <td align="center">{{ item.id }}</td>
                        <td align="center">{{ item.name }}</td>
                        <td align="center">{{ item.appid }}</td>
                        <td align="center">
                            <template v-if="item.type == 1">
                                公众号模板消息<br>
                                {{ item.mp_appid }}
                            </template>
                            <template v-else>
                                小程序服务通知
                            </template>
                        </td>
                        <td align="center">{{ item.template_id }}</td>
                        <td align="center">{{ item.title }}</td>
                        <td align="center" v-html="item.description" style="text-align: left"></td>
                        <td align="center">
                            <a class="btn btn-primary" @click="openUrl('测试发送','{:U('send')}&id='+item.id)">测试发送</a>
                            <a class="btn btn-info" @click="openUrl('编辑模板','{:U('temp')}&id='+item.id)">编辑</a>
                            <a @click="deleteBtn(item.id)" class="btn btn-danger" href="javascript:;">删除</a>
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
            openUrl: function (title, url) {
                var that = this;
                layer.open({
                    title: title,
                    type: 2,
                    area: ['800px', '600px'],
                    content: url,
                    end: function () {
                        that.getList()
                    }
                });
            },
            deleteBtn: function (id) {
                layer.confirm('是否确定删除', {}, function () {
                    $.ajax({
                        url: '{:U("delTemp")}',
                        data: {id: id},
                        type: 'post',
                        dataType: 'json',
                        success: function (res) {
                            if (res.status) {
                                location.reload();
                            } else {
                                layer.msg(res.msg)
                            }
                        }
                    })
                })
            },
            toPage: function (page) {
                this.page = parseInt(page)
                this.getList()
            },
            getList: function () {
                var that = this
                $.ajax({
                    url: '{:U("getList")}',
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
            }
        },
        mounted: function () {
            this.getList()
        }
    });
</script>
</body>
</html>
