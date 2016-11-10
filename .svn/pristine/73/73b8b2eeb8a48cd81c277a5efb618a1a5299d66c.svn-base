/**
 * 翻页控件 
 */
var PPage = (function(window) {
    return function(pid, curNum, maxNum, funcName, elevator,totalRecord,pageRecord) {
        //直达页面UI是否出现 , 为true 时出现
        elevator = elevator || false;
        function getAHtml(num) {
            return '<a href="javascript:;" onclick="' + funcName + '(' + num + ');return false;">' + num + '</a>';
        };
        /***总页面，每页页数**/
        function getTotalAndPagerInfoHtml(totalRecord,pageRecord){
            if(totalRecord){
                // totalRecord=totalRecord||'n';
                pageRecord=pageRecord||5;
                return '<div class="pager-showpart"><div class="data-numbers"><span>共 <span class="total-numbers">'+totalRecord+'</span> 条记录，每页显示 <span class="page-numbers">'+pageRecord+'</span> 条信息</span></div></div>';
            }else{
                return '';
            }
        }
        var page = typeof pid == "string" ? document.getElementById(pid) : pid,
            sPrev = '',
            sNext = '',
            sResult = '',
            sp = '',
            sn = '',
            sCur = '<a class="p-disabled p-current" href="javascript:void(null);">'+ curNum +'</a>', //'<em>' + curNum + '</em>',
            gd = '<span>...</span>',
            prevNum,
            nextNum,
            i,
            elevatorHtml = '';
        if (curNum == 1) {
            sPrev = '<a class="p-disabled" href="javascript:void(null);">上一页</a>';
        } else {
            prevNum = curNum - 1;
            sPrev = '<a href="javascript:;" onclick="' + funcName + '(' + prevNum + ');return false;">上一页</a>';
        }
        if (curNum == maxNum) {
            sNext = '<a class="p-disabled" href="javascript:void(null);">下一页</a>';
        } else {
            nextNum = curNum + 1;
            sNext = '<a href="javascript:;" onclick="' + funcName + '(' + nextNum + ');return false;">下一页</a>';
        }
        if (maxNum <= 6) {
            for (i = 1; i < curNum; i++) {
                sp += getAHtml(i);
            }
            for (i = curNum + 1; i <= maxNum; i++) {
                sn += getAHtml(i);
            }
            sResult = sPrev + sp + sCur + sn + sNext;
        } else {
            if (curNum <= 4) {
                for (i = 1; i < curNum; i++) {
                    sp += getAHtml(i);
                }
                for (i = curNum + 1; i <= 5; i++) {
                    sn += getAHtml(i);
                }
                sNext = getAHtml(maxNum) + sNext;
                sResult = sPrev + sp + sCur + sn + gd + sNext;
            } else {
                sPrev = sPrev + getAHtml(1);
                if (curNum < maxNum - 3) {
                    for (i = curNum - 2; i < curNum; i++) {
                        sp += getAHtml(i);
                    }
                    for (i = curNum + 1; i <= curNum + 2; i++) {
                        sn += getAHtml(i);
                    }
                    sNext = getAHtml(maxNum) + sNext;
                    sResult = sPrev + gd + sp + sCur + sn + gd + sNext;
                } else {
                    for (i = maxNum - 4; i < curNum; i++) {
                        sp += getAHtml(i);
                    }
                    for (i = curNum + 1; i <= maxNum; i++) {
                        sn += getAHtml(i);
                    }
                    sResult = sPrev + gd + sp + sCur + sn + sNext;
                }
            }
        }
        if (elevator === true) {
            var n1 = +new Date(),
                n2 = parseInt(Math.random() * 1000),
                pagetTextId = "j-page-num" + n1 + n2,
                pageWarningId = "j-page-elevator-warning" + n1 + n2,
                timeout = "time" + n1 + n2,
                time = 2000,
                f = funcName.replace(/\./g, "_");
            window['PPage_elevator_' + f] = function(v, max) {
                v = +v;
                if (!v || typeof v !== "number" || v > max || v < 0) {
                    var pageWarningElem = document.getElementById(pageWarningId);
                    clearTimeout(timeout);
                    pageWarningElem.style.display = "block";
                    timeout = setTimeout(function() {
                        pageWarningElem.style.display = "none";
                    }, time);
                    return;
                }
                eval(funcName + '(' + v + ')');
            };
            elevatorHtml = '<span class="page-elevator-wrap"><div id="' + pageWarningId + '" class="page-elevator-warning" style="display:none;"><span>最大页数 ' + maxNum + '</span><b></b><i></i></div><input class="page-txt" type="text" id="' + pagetTextId + '" autocomplete="off" style="ime-mode:disabled" title="请输入页码，最大页数：' + maxNum + '" /><button class="page-btn" onclick="PPage_elevator_' + f + '(document.getElementById(\'' + pagetTextId + '\').value,' + maxNum + ');return false;">确定</button></span>';
        }
        page.innerHTML =getTotalAndPagerInfoHtml(totalRecord,pageRecord) +"<div class='pager-operatepart'>"+sResult + elevatorHtml+"</div>";
    };
})(window); 