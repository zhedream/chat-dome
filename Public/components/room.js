Vue.component('room', {
    props: ['id','title','contents','clients','uname'],
    data: function () {
        return {
            name: '',
            content: '',
            members: {},
            viewindex: 0,
            views: ['room'],
            to:-1,
        }
    },
    methods: {
        send: function () {
            if(this.to==-1){

                ms.send('all', {
                    to:this.to,
                    message:this.content,
                });
            }else{
                ms.send('to',{
                    to:this.to,
                    message:this.content,
                })
            }
            this.content = '';
        },
        sendTo:function(){

        },
        select:function(conn_id){
            this.to =conn_id
        }
    },
    created(){
        // 进入房间 连接 服务器
        connect();
        
    },
    template:
        `<div class="room">
            <h1>Simple Chat</h1>
            <span>欢迎你{{uname}}</span>
            <br>
            
            <div id="content">
                <div v-for="v in contents">
                    {{v}}
                </div>
            </div>
            <input type="text" v-model="content" id="msg">
            <button type="button" v-show="to==-1" @click="send" id="send" >群发</button>
            <button type="button" v-show="to!=-1" @click="send" id="send" >对{{to}}号说</button>
            <div id="list">
                <a href="javascript:;" @click="select(-1)">全部</a>
                <div @click="select(con.conn_id)" v-for="con in clients"><a href="javascript:;">{{con.conn_id}}-{{con.uid}}-{{con.uname}}</a></div>
                
            </div>
        </div>`,

})