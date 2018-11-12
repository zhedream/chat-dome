class MyWebSocket{

    constructor(conf){
        this.WebSocket = new WebSocket(conf.url);
        this.WebSocket.onopen = function(e){
            conf.onopen(e)
        }
        this.WebSocket.onclose = function (e){
            conf.onclose(e)
        }
        this.WebSocket.onerror = function(e){
            conf.onerror(e)
        }
        this.WebSocket.onmessage = function(e){
            console.log('AAA',e.data);
            
            let data = JSON.parse(e.data) || e.data;
            conf.onmessage(data,e)
        }
    }

    send(action,data=[]){
        
        data = this.json_encode({'action':action,'data':data});
        this.WebSocket.send(data)
    }

    getWs(){
        return this.WebSocket;
    }

    json_encode(data){
        return JSON.stringify(data);
    }

    json_decode(str){
        return JSON.stringify(str);
    }



}