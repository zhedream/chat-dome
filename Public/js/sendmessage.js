ws.exec = function(data,dispatch=''){
    var data = {
        'data':data,
        'dispatch':dispatch
    }
    let data = JSON.stringify(data)
    this.send(data)
}

ws.sendAll = function(data,action='sendAll'){

    let data = JSON.stringify({msg,action})
    this.send(data)
}

