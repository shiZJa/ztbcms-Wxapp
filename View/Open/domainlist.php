<?php if (!defined('CMS_VERSION')) {
    exit();
} ?>
<Admintemplate file="Common/Head"/>
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
    <Admintemplate file="Common/Nav"/>
    <h3 style="margin-bottom: 20px;">
        <span>appid = {$appid}</span>
        <span style="float: right;">
            <a class="btn btn-primary" href="{:U('addDomain',['appid'=>$appid])}">添加域名</a>
        </span>
    </h3>
    <div>
        <div id="app" class="table_list">
            <table width="100%">
                <thead>
                <tr class="h_a">
                    <td align="center">请求方式</td>
                    <td align="center">域名</td>
                </tr>
                </thead>
                <tbody>
                <template v-for="(item,key) in lists">
                    <tr>
                        <td align="center">{{ key }}</td>
                        <td align="center">
                            <p v-for="k in item">
                                {{ k }} <a @click="deleteItem(k,key)" href="javascript:;">【删除】</a>
                            </p>
                        </td>
                    </tr>
                </template>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="//cdn.bootcss.com/layer/3.0.1/layer.js"></script>
<script src="//cdn.bootcss.com/vue/2.1.5/vue.min.js"></script>
<script>
    new Vue({
        el: "#app",
        data: {
            appid: '{$appid}',
            lists: [],
        },
        methods: {
            deleteItem: function (domain, key) {
                var that = this
                layer.confirm('是否删除该域名？', {}, function (res) {
                    $.ajax({
                        url: '{:U("deleteDomain")}',
                        data: {appid: this.appid, key: key, domain: domain},
                        type: 'post',
                        dataType: 'json',
                        success: function (res) {
                            if (res.status) {
                                layer.msg('删除成功', function () {
                                    that.getList()
                                })
                            } else {
                                layer.msg('操作失败')
                            }
                        }
                    })
                })
            },
            getList: function () {
                var that = this
                $.ajax({
                    url: '{:U("domainList")}',
                    data: {appid: this.appid},
                    dataType: 'json',
                    success: function (res) {
                        console.log(res)
                        var data = res.data
                        that.lists = data
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
