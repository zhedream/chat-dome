var app = new Vue({
    delimiters: ['v{', '}'],
    el: '#app',
    data: {
        uname: '',
        contents: {
            'all':[]
        },
        talks:[],// 对话列表
        talkto:-1,
        members: {},
        viewindex: 0,
        views: ['room'],
        clients:{},
        roomData: {
            id: 1,
            title: '闲聊',
            contents: {
                'all':[
                    {}
                ],
                0:[

                ],
            },
            clients:{},
        }
    },
    computed: {
        currentView() {
            return this.views[this.viewindex];
        }
    },
    methods: {
        send: function () {
            ms.send('message', this.content)
        },
        into:function(){

        },
        listclick:function(client){
            console.log(client);
            this.talkto = client.conn_id;
            for (const key in this.talks) {
                if (this.talks.hasOwnProperty(key)) {
                    const talk = this.talks[key];
                    if(talk.conn_id==client.conn_id){
                        console.log('存在于takls');
                        return;
                    }
                }
            }
            this.talks.push(client);
            
        },
        talkclick:function(conn_id){
            console.log('talkto',conn_id);
            this.talkto = conn_id;
            

        }
    },
    beforeCreate: function () {
        console.log('创建前执行');
    },
    created: function () {
        console.log('创建后');
        this.uname = uname;
        this.uid = uid;
        connect();// 连接服务器
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