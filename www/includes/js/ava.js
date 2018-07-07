;
"use strict";
//console.log(page);
//console.log(ava);
$.post('./includes/getAva.php', {data: ava, page: page}, function(data) {
    //console.log(data);
    //console.log(data.length);
    for(let i1 = 0; i1 < data.length; i1++){
        for(let i = 0; i < data[i1].length; i++){
            document.getElementById(data[i1][i].id).src = data[i1][i].url;
            //console.log(data[1][i]);
        }
    }
    
});
//console.log(JSON.stringify(ava[0]));