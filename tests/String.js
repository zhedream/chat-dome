// 不是用 字符串函数 把 
function str2int(s) {
    let arr = [];
    for (let index = 0; index < s.length; index++) {
        const element = s[index];
        arr.push(element * 1)
    }
    var a = arr.reduce(function (x, y) {
        return x * 10 + y;
    });
    return a;
}

// 首字母 大写 其余 小写
function titleCase3(s) {
    return s.toLowerCase().split(/\s+/).map(function (item, index) {
        return item.slice(0, 1).toUpperCase() + item.slice(1);
    }).join(' ');
}
// 把字符串 首字母 大写 返回
function titleCase4(s) {
    return s.split(/\s+/).map(function (item, index) {
        return item.slice(0, 1).toUpperCase() + item.slice(1);
    }).join(' ');
}

class a extends b{
    
}