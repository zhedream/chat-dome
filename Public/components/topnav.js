Vue.component('topnav', {
    props:['name'],
    template: `<div class="topnav">
    <div class="topnav_bd w990 bc">
        <div class="topnav_left">

        </div>
        <div class="topnav_right fr">
            <ul>
                <li>您好，欢迎来到京西！{{name}}
                    <a v-if="name==''" href="login.html">登录</a> 
                    <a v-if="name==''" href="regist.html">免费注册</a> </li>
                <li class="line">|</li>
                <li>我的订单</li>
                <li class="line">|</li>
                <li>客户服务</li>

            </ul>
        </div>
    </div>
</div>`,

})