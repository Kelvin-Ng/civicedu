var increase_height_interval;
 var menu_displayed = false;
function gebid(id)
        {
            return document.getElementById(id);
        }
		
function show_menu(page)
        {
            delete_menu();
            var newspan = document.createElement('span');
            newspan.setAttribute('id', "menu");
            var x, y;
            if (page == 1)
            {
                x = GetX(activities);
                y = GetY(activities);
                newspan.innerHTML = "<span class=\"menu_items\" onclick=\"readfile('activities.php')\">Application forms</span>";
            }
			
            else if (page == 2)
            {
                x = GetX(doc);
                y = GetY(doc);
                newspan.innerHTML = "<span class=\"menu_items\" onclick=\"readfile('Annual_Plan.html')\">Annual Plan</span>";
            }
			/*
            else if (page == 3)
            {
                x = GetX(fr_item);
                y = GetY(fr_item);
                newspan.innerHTML = "<span class=\"menu_items\" onclick=\"readfile('famous_racer.php', 'page=1')\">Hamilton</span><br>\
                                    <span class=\"menu_items\" onclick=\"readfile('famous_racer.php', 'page=2')\">Sebastian Vettel</span><br>\
                                    <span class=\"menu_items\" onclick=\"readfile('famous_racer.php', 'page=3')\">Michael Schumacher</span>";
            }
			*/
            //newspan.setAttribute('style', "cursor: pointer; background-color: darkgrey; color: white; position: absolute; left: " + x + "; top: " + (y + bar.clientHeight) + "; width: 85px");
            newspan.className = "menu";
            newspan.style.left = x;
            newspan.style.top = y + bar.clientHeight;
            //newspan.setAttribute('onmouseout', "move_out_menu(event)");
            gebid('body').appendChild(newspan);
            newspan.style.fontWeight = "bold";
            newspan.style.width = newspan.clientWidth + 5 + "px";
            //newspan.style.overflow = "hidden";
            newspan.style.fontWeight = "";
            increase_height_interval = setInterval("increase_height('menu', " + newspan.clientHeight + ")", 1);
            newspan.style.height = 0;
            menu_displayed = true;
        }
		
		function delete_menu()
        {
            if (menu_displayed)
            {
                clearInterval(increase_height_interval);
                increase_height_interval = 0;
                gebid('body').removeChild(gebid('menu'));
                menu_displayed = false;
            }
        }