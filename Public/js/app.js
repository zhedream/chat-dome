var app = new Vue({

    el: '#app',
    data: {
        uname: '',
        content: '',
        members: {},
        viewindex: 0,
        views: ['room'],
        roomData: {
            id: 1,
            title: '闲聊',
            contents: [
                '欢迎来到本闲聊房间',
                '注意文明用语',
            ],
            clients:{},
        }
    },
    computed: {
        currentView() {
            return this.views[this.viewindex];
        }
    },
    methods: {
        msg: function (text = '') {
            lay.msg(text)
        },
        typing: function () {
            this.msg()
        },
        send: function () {
            ms.send('message', this.content)
        },
        into:function(){

        }
    },
    beforeCreate: function () {
        console.log('创建前执行');
    },
    created: function () {
        console.log('创建后');
        this.uname = uname;
        this.uid = uid;
    }

})


// lay = layui.use(['layer'], function () {
//     var layer = layui.layer;
//     this.msg = function (text = 'hello') {
//         layer.msg(text, {
//             time: 850
//         });
//     }
// });