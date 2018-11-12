class MySocket extends WebSocket{

    //"ws://127.0.0.1:2347"
    constructor(str){
        super(str)

    }

    send(data){
        data = this.json_encode(data);
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