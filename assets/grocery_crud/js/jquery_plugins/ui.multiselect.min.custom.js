/* jQuery UI Multiselect | https://github.com/michael/multiselect */
(function(b) {
    b.widget("ui.multiselect", {options: {
        groupList: !1,
        mediaList: !1,
        removeAll: true,
        addAll: true,
        droppable: true, 
        unique: false, 
        sortable: !0, 
        searchable: !0, 
        doubleClickable: !0, 
        animated: "fast", 
        show: "slideDown", 
        hide: "slideDown", 
        dividerLocation: 0.5, 
        availableFirst: !1,
        refreshAvailableList: !0,
        nodeComparator: function(a, b) {
            var d = a.text(), e = b.text();
            return d == e ? 0 : d < e ? -1 : 1
        }}, 

        _create: function() {
            this.element.hide();
            this.element.css({height: +500, width : +850});
            this.id = this.element.attr("id");
            this.container = b('<div class="ui-multiselect ui-helper-clearfix ui-widget"></div>').insertAfter(this.element);
            this.count = 0;
            this.selectedContainer = b('<div class="selected"></div>').appendTo(this.container);
            this.availableContainer = b('<div class="available"></div>')[this.options.availableFirst ? "prependTo" : "appendTo"](this.container);
            this.selectedActions = b('<div class="actions ui-widget-header ui-helper-clearfix"><span class="count">0 ' + b.ui.multiselect.locale.itemsCount + '</span><a href="#" class="remove-all">' + b.ui.multiselect.locale.removeAll + "</a></div>").appendTo(this.selectedContainer);
            
            this.selectGroup = '<select id="selectGroup" style="background-color: #DDD;padding:2px; margin-top: 4px; height: 20px;"></select>' ;
            
            this.availableActions = b('<div class="actions ui-widget-header ui-helper-clearfix">'+this.selectGroup+'<input type="text" class="search empty ui-widget-content ui-corner-all"/><a href="#" class="add-all">' + b.ui.multiselect.locale.addAll + "</a></div>").appendTo(this.availableContainer);
            this.selectedList = b('<ul class="selected connected-list"><li class="ui-helper-hidden-accessible"></li></ul>').bind("selectstart", function() {
                return!1
            }).appendTo(this.selectedContainer);
            this.availableList = b('<ul class="available connected-list"><li class="ui-helper-hidden-accessible"></li></ul>').bind("selectstart", function() {
                return!1
            }).appendTo(this.availableContainer);
            var a = this;
            
            this.container.width(this.element.width() + 1);
            this.selectedContainer.width(Math.floor(this.element.width() * this.options.dividerLocation));
            this.availableContainer.width(Math.floor(this.element.width() * (1 - this.options.dividerLocation)));
            this.availableActions.height(this.selectedActions.height()-1);
            this.selectedList.height(Math.max(this.element.height() - this.selectedActions.height(), 1));
            this.availableList.height(Math.max(this.element.height() - this.availableActions.height(), 1));
            this.options.animated || (this.options.show = "show", this.options.hide = "hide");
            this._populateLists(this.element.find("option"));
            this.options.sortable && this.selectedList.sortable({placeholder: "ui-state-highlight", axis: "y", 
                update: function() {
                    a.selectedList.find("li").each(function() {
                        b(this).data("optionLink") && b(this).data("optionLink").remove().appendTo(a.element)
                    });
                    
                    a.selectedList.find("li").not(".ui-helper-hidden-accessible").each(function(index, value) {
                       $(this).find(".count-item").removeClass("ui-icon ui-icon-arrowthick-2-n-s").html((index + 1) + " : ");
                    });
                }, receive: function(c, d) {
                    d.item.data("optionLink").attr("selected", !0);
                    a.count += 1;
                    a._updateCount();
                    a.selectedList.children(".ui-draggable").each(function() {
                        b(this).removeClass("ui-draggable");
                        b(this).data("optionLink", d.item.data("optionLink"));
                        b(this).data("idx", d.item.data("idx"));
                        a._applyItemState(b(this), !0)
                    });
                    setTimeout(function() {
                        d.item.remove()
                    }, 1)
                }});
            this.options.searchable ? this._registerSearchEvents(this.availableContainer.find("input.search")) : b(".search").hide();
            
            
            if(this.options.removeAll === true){
                this.container.find(".remove-all").click(function() {
                    a._populateLists(a.element.find("option").removeAttr("selected"));
                    return!1
                });
            } else {
                this.container.find(".remove-all").hide()
            }
           
            if(this.options.addAll === true){
                this.container.find(".add-all").click(function() {
                    var c = a.element.find("option").not(":selected");
                    1 < a.availableList.children("li:hidden").length ? a.availableList.children("li").each(function(a) {
                        b(this).is(":visible") && b(c[a - 1]).attr("selected", "selected")
                    }) : c.attr("selected", "selected");
                    a._populateLists(a.element.find("option"));
                    return!1
                })
            } else {
                this.container.find(".add-all").hide();
            }
            
            if(this.options.groupList !== false && this.options.groupList !== null && this.options.mediaList !== false && this.options.mediaList !== null){
                $.each(this.options.groupList, function(key, value) {   
                    $('#selectGroup')
                        .append($("<option></option>")
                        .attr("value",value.cat_ID)
                        .text(value.cat_name))
                        .change(function(e){
                        var catId = $(e.target).val();
                        a._filterGroup(catId, a.availableList);
                    }).change(); 
               });
            }else{
                 $(this.selectGroup).hide();
            }
            
            
            
        }, 
        destroy: function() {
            this.element.show();
            this.container.remove();
            b.Widget.prototype.destroy.apply(this, arguments)
        }, 
        _populateLists: function(a) {
            this.selectedList.children(".ui-element").remove();
            if(this.options.refreshAvailableList == true){
                this.availableList.children(".ui-element").remove();
            }
            this.count = 0;
            var c = this;
            b(a.map(function(a) {
                var b = c._getOptionNode(this).appendTo(this.selected ? c.selectedList : c.availableList).show();
                this.selected && (c.count += 1);
                c._applyItemState(b, this.selected);
                b.data("idx", a);
                return b[0]
            }));
            this._updateCount();
            c._filter.apply(this.availableContainer.find("input.search"), [c.availableList])
        }, 
        _updateCount: function() {
            this.element.trigger("change");
//            this.selectedContainer.find("span.count").text(this.count + " " + b.ui.multiselect.locale.itemsCount);
            var $list = this.selectedList.find("li").not(".ui-helper-hidden-accessible");
            this.count = $list.size();
            this.selectedContainer.find("span.count").text(this.count + " " + b.ui.multiselect.locale.itemsCount);
            
            $list.each(function(index, value){
                $(this).find(".count-item").removeClass("ui-icon ui-icon-arrowthick-2-n-s").html((index + 1) + " : ");
            });
            
//            $(".count-item").not(".ui-helper-hidden");
             
            this.updateProcessBar();
        }, 
        _getOptionNode: function(a) {
            var a = b(a), c = b('<li class="ui-state-default ui-element" title="' + a.text() + '"><span class="ui-icon"/> <span class="count-item"> test </span> ' + a.text() + '<a href="#" class="action"><span class="ui-corner-all ui-icon"/></a></li>').hide();
            c.data("optionLink", a);
            return c
        }, _cloneWithData: function(a) {
           
        var b = a.clone(!1, !1);
            b.data("optionLink", a.data("optionLink"));
            b.data("idx", a.data("idx"));
            return b
        }, 
        _setSelected: function(a, c) {
            if (c) {
                var d = this._cloneWithData(a);
                if (this.options.unique) {
                    a[this.options.hide](this.options.animated, function() {
                        b(this).remove()
                    });
                }else{
                    var option = a.data("optionLink")
                    $('#field-Media')
                        .append($("<option selected='selected'></option>")
                        .attr("value",option.val() + "/d" + this.count)
                        .text(option.html()));
                        
//                    a.data("optionLink", option.val(option.val() + "/d" + this.count))
//                    $("#field-Media").append('<optionvalue="2">ATT116C.MPG</option>')
                }

                d.appendTo(this.selectedList).hide()[this.options.show](this.options.animated);
                this._applyItemState(d, !0);
                return d
            }
            a.data("optionLink").attr("selected", c);
            var e = this.availableList.find("li"), g = this.options.nodeComparator, d = null, f = a.data("idx"), h = g(a, b(e[f]));
            if (h)
                for (; 0 <= f && f < e.length; ) {
                    if (0 < h ? f++ : f--, h != g(a, b(e[f]))) {
                        d = e[0 < h ? f : f + 1];
                        break
                    }
                }
            else
                d = e[f];
            e = this._cloneWithData(a);
            if (this.options.unique) {
                d ? e.insertBefore(b(d)) : e.appendTo(this.availableList);
            }
            a[this.options.hide](this.options.animated, function() {
                b(this).remove();
            });
            e.hide()[this.options.show](this.options.animated);
            this._applyItemState(e, !1);
            return e
        }, 
        _applyItemState: function(a, b) {
            b ? (this.options.sortable ? a.children("span").addClass("ui-icon-arrowthick-2-n-s").removeClass("ui-helper-hidden").addClass("ui-icon") : a.children("span").removeClass("ui-icon-arrowthick-2-n-s").addClass("ui-helper-hidden").removeClass("ui-icon"), a.find("a.action span").addClass("ui-icon-minus").removeClass("ui-icon-plus"), this._registerRemoveEvents(a.find("a.action"))) : (a.children("span").removeClass("ui-icon-arrowthick-2-n-s").addClass("ui-helper-hidden").removeClass("ui-icon"), a.find("a.action span").addClass("ui-icon-plus").removeClass("ui-icon-minus"), this._registerAddEvents(a.find("a.action")));




            this._registerDoubleClickEvents(a);
            this._registerHoverEvents(a)
        }, 
        _filter: function(a) {
            var search = b(this);
            var d = a.children("li");
            
            var value = b.trim(search.val().toLowerCase());
            var g = [];
            if (value)
            {
                var a = d.map(function() {
                    var temp = b(this).find(".count-item").html();
                    
                    return this.innerText.replace(temp, "").trim().toLowerCase();
//                    return b(this).text().toLowerCase()
                });
                d.hide();
                a.each(function(a) {
                    -1 < this.indexOf(value) && g.push(a);
                });
                b.each(g, function() {
                    var li = b(d[this]);
                    var src= li.data("optionLink");
                    var catId = $("#selectGroup").val();
                    
                    if(src){
                        var mediaId = src.val();
                        var media = mediaList[mediaId];
                        if(media !== null){
                            //ถ้าเป็น all หรือ media group ตรงกัน ให้แสดง
                            if(media.type == $("#field-pl_type").val()){
                            //ถ้าเป็น All ให้โชว์หมด
                                if(catId == 0){
                                    li.show();
                                    return;
                                }
                                if(media.cat_ID === catId){
                                    li.show();
                                }else{
                                    li.hide();
                                }
                            }else{
                                //ถ้าไม่ตรงกับที่เลือกให้ hide
                                li.hide();
                            }

                        }else{
                            li.hide();
                        }
                    }
                });
                
//                d.hide();
//                a.each(function(a) {
//                    -1 < this.indexOf(value) && g.push(a)
//                });
//                b.each(g, function() {
//                    b(d[this]).show()
//                });
                
            } else {
                d.each(function() {
                    var li = b(this);
                    var src= li.data("optionLink");
                    var catId = $("#selectGroup").val();
                    
                    if(src){
                        var mediaId = src.val();
                        var media = mediaList[mediaId];
                        if(media != null){
                            //ถ้าเป็น all หรือ media group ตรงกัน ให้แสดง
                            if(media.type == $("#field-pl_type").val()){
                            //ถ้าเป็น All ให้โชว์หมด
                                if(catId == 0){
                                    li.show();
                                    return;
                                }
                                if(media.cat_ID === catId){
                                    li.show();
                                }else{
                                    li.hide();
                                }
                            }else{
                                //ถ้าไม่ตรงกับที่เลือกให้ hide
                                li.hide();
                            }
                        }
//                        if(mediaList[mediaId].cat_ID === catId){
//                            li.show();
//                        }else{
//                            li.hide();
//                        }
                    }
                });
                
//                d.show();
            }
        }, 
        _filterGroup: function(catId, list) {
            var target = b(this);
            var d = list.children("li");
            
            var options = this.options;
            
            b.each(d, function(index, val){
                var li = b(val);
                
                var src= li.data("optionLink");
                if(src){
                    var mediaId = src.val();
                    var media = options.mediaList[mediaId];
                    if(media != null){
                        //ถ้า type == ตัวที่เลือกให้เข้าไปตรวจสอบต่อ 
                        if(media.type == $("#field-pl_type").val()){
                            //ถ้าเป็น All ให้โชว์หมด
                            if(catId == 0){
                                li.show();
                                return;
                            }
                            if(media.cat_ID === catId){
                                li.show();
                            }else{
                                li.hide();
                            }
                        }else{
                            //ถ้าไม่ตรงกับที่เลือกให้ hide
                            li.hide();
                        }
                        
                    }else{
                        li.hide();
                    }
                    
                }
            });
        }, 
        _registerDoubleClickEvents: function(a) {
            this.options.doubleClickable && a.dblclick(function() {
                a.find("a.action").click()
            })
        }, 
        _registerHoverEvents: function(a) {
            a.removeClass("ui-state-hover");
            a.mouseover(function() {
                b(this).addClass("ui-state-hover")
            });
            a.mouseout(function() {
                b(this).removeClass("ui-state-hover")
            })
        }, 
        _registerAddEvents: function(a) {
            var c = this;
            a.click(function() {
                c._setSelected(b(this).parent(), !0);
                c.count += 1;
                c._updateCount();
                return!1
            });
            this.options.sortable && a.each(function() {
                if(c.options.droppable === true){
                    b(this).parent().draggable({connectToSortable: c.selectedList, helper: function() {
                        var a = c._cloneWithData(b(this)).width(b(this).width() - 50);
                        a.width(b(this).width());
                        return a
                    }, appendTo: c.container, containment: c.container, revert: "invalid"})
                }
            });
        }, 
        _registerRemoveEvents: function(a) {
            var c = this;
            a.click(function() {
                c._setSelected(b(this).parent(), !1);
                c.count -= 1;
                c._updateCount();
                return!1
            })
        }, 
        _registerSearchEvents: function(a) {
            var c = this;
            a.focus(function() {
                b(this).addClass("ui-state-active")
            }).blur(function() {
                b(this).removeClass("ui-state-active")
            }).keypress(function(a) {
                if (13 == a.keyCode)
                    return!1
            }).keyup(function() {
                c._filter.apply(this, [c.availableList])
            })
        },updateProcessBar: function(){
            if(this.options.isUpdateProcessBar){
                var countUsage = 0;
                this.selectedList.find("li").each(function(li){
                    var src = $(this).data("optionLink");
                    if(src){
                        var mediaId = src.val();
                        var index = mediaId.indexOf("/");
                        if(index !== -1){
                            mediaId = mediaId.substring(0, index);
                        }
                        var media = mediaList[mediaId];
                        if(media == null){
                            $(this).remove();
                            $('#field-Media').find("option:selected").each(function(){
                                var $value = $(this).val();
                                $value = $value.substring(0, $value.indexOf("/"));
                                if(mediaId == $value){
                                    $(this).prop({selected: false});
                                    return;
                                }
                            });
                        }else{
                            var lenght = media.lenght;
                            countUsage += parseInt((lenght == null ? 0 : lenght));
                        }
                    } 
                });
//                var $progressbarValue = $("#field-pl_usage").val();

//                var $lenght = $("#field-pl_lenght").val();
//                var percen = (parseFloat(countUsage) / parseFloat($lenght) * 100).toFixed(2);
//                var intt = percen / countUsage;
//                var pGress = setInterval(function() {
//                    var pVal = $('#progressbar').progressbar('option', 'value');
//                    var pCnt = !isNaN(pVal) ? (pVal + 1) : 1;
//                    if (pCnt > percen) {
//                        clearInterval(pGress);
//                    } else {
//        //                var pCnt = !isNaN(pVal) ? (pVal - 1) : 1;
//        //                        var $lenght = $("#field-pl_lenght").val();
//        //                        var percen = (parseFloat(pCnt) / parseFloat($lenght) * 100).toFixed(2);
//        //                        if (pCnt < countUsage) {
//        //                            clearInterval(gLoop);
//        //                        } else {
//        //                            $('#progressbar').progressbar({value: percen});
//        //                            $(".progress-label").html(pCnt + ":" + $lenght + " (" + percen + "%)");
//        //                        }
//
//                        $('#progressbar').progressbar({value: pCnt});
//                        $(".progress-label").html( (pCnt/intt).toFixed(0) + ":" + $lenght + " (" + pCnt + "%)");
//                    }
//                    
//                    if(pCnt > 100 ){
//                        percen--;
//                    }
//                },10);

                var $string = getFormatTime(timeToString($("#field-pl_lenght").val()));
                var $arr = $string.split(":");
                var $lenght = parseInt($arr[0]) * 3600 + parseInt($arr[1]) * 60 + parseInt($arr[2]); 
//                var $lenght = $("#field-pl_lenght").val();
                var percen = ((countUsage / $lenght) * 100).toFixed(2);
                
                if(parseInt(countUsage) == 0){
                    percen = 0;
                } else if($lenght == "0"){
                    percen = 100;
                }
                
                $('#progressbar').progressbar({value: parseInt(percen)});
                $(".progress-label").html( getFormatTime(countUsage) + " (" + percen + "%)");
                $("#field-pl_usage").val(countUsage);
//                $(".progress-label").html(countUsage);
//                var $lenght = parseFloat($("#field-pl_usage").val());
//                var isUp = countUsage > $lenght;
//                var gLoop = setInterval(function() {
//                    var pVal = $('#progressbar').progressbar('option', 'value');
//                    var tempPercen = 0;
//                    if(isUp){
//                        var pCnt = !isNaN(pVal) ? (pVal + 1) : 1;
//                        var $lenght = $("#field-pl_lenght").val();
//                        var percen = (parseFloat(pCnt) / parseFloat($lenght) * 100).toFixed(2);
//                        tempPercen = percen;
//                        if (pCnt > percen) {
//                            clearInterval(gLoop);
//                        } else {
//                            $('#progressbar').progressbar({value: percen});
//                            $(".progress-label").html(pCnt + ":" + $lenght + " (" + percen + "%)");
//                        }
//                    }else{
//                        var pCnt = !isNaN(pVal) ? (pVal - 1) : 1;
//                        var $lenght = $("#field-pl_lenght").val();
//                        var percen = (parseFloat(pCnt) / parseFloat($lenght) * 100).toFixed(2);
//                        tempPercen = percen;
//                        if (pCnt < percen) {
//                            clearInterval(gLoop);
//                        } else {
//                            $('#progressbar').progressbar({value: percen});
//                            $(".progress-label").html(pCnt + ":" + $lenght + " (" + percen + "%)");
//                        }
//                    }
//                    if(pVal == tempPercen || pCnt > 100){
////                        clearInterval(gLoop);
////                        $('#progressbar').progressbar({value: pCnt});
////                        var $lenght = $("#field-pl_lenght").val();
////                        $(".progress-label").html(pCnt + ":" + $lenght + " (" + (parseFloat(pCnt) / parseFloat($lenght) * 100).toFixed(2) + "%)");
//                        
//                        $("#field-pl_usage").val(countUsage);
//                    }
//                    
//                },5);
                
//                $('#progressbar').progressbar({value: countUsage});
//                var $lenght = $("#field-pl_lenght").val();
////                $(".progress-label").html(countUsage);
//                $(".progress-label").html(countUsage + ":" + $lenght + " (" + (parseFloat(countUsage) / parseFloat($lenght) * 100).toFixed(2) + "%)");
//                $("#field-pl_lenght").val(countUsage);
            
            }
        }});
    b.extend(b.ui.multiselect, {locale: {addAll: "Add all", removeAll: "Remove all", itemsCount: "items selected"}})
})(jQuery);