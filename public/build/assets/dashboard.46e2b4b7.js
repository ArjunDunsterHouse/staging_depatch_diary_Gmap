import{s as B,j as K,r as u,o as _,c as C,w as t,a as e,u as k,h as y,k as F,t as c,F as P,b as V,V as A,_ as R,d as l,l as $,n as q,v as L,p as O,i as j,e as J,g as H,f as G,q as W,x as I,y as N,z as E,S as Q}from"./app.3f6cd2bb.js";import"./moment.9709ab41.js";import{S as X,a as Z}from"./ScrollToBottom.a7dcdc82.js";const ee=["value"],oe=V(" Search "),te={__name:"SearchData",setup(r){const o=B(),s=K(),b=()=>{A.setCookie("selected_branch",s.selectedBranch,{path:"/"}),A.setCookie("selected_date",s.selectedDate,{path:"/"}),s.selectedBranch=A.getCookie("selected_branch"),s.selectedDate=A.getCookie("selected_date"),o.SearchRequestedData(s.selectedBranch,s.selectedDate)};return(d,a)=>{const m=u("FormKit"),p=u("b-col"),g=u("b-row");return _(),C(g,{class:"searchForm",style:{"margin-left":"1px"}},{default:t(()=>[e(m,{type:"group"},{default:t(()=>[e(p,{cols:"3"},{default:t(()=>[e(m,{type:"select",label:"Select Branch",name:"SelectedBranch",modelValue:k(s).selectedBranch,"onUpdate:modelValue":a[0]||(a[0]=f=>k(s).selectedBranch=f),onChange:k(s).setCookiesForInputData},{default:t(()=>[(_(!0),y(P,null,F(k(s).branches,f=>(_(),y("option",{id:"searchDropdown",key:f.branch_id,value:f.branch_id},c(f.branch_location),9,ee))),128))]),_:1},8,["modelValue","onChange"])]),_:1}),e(p,{cols:"3"},{default:t(()=>[e(m,{type:"date",label:"select date",validation:"required",modelValue:k(s).selectedDate,"onUpdate:modelValue":a[1]||(a[1]=f=>k(s).selectedDate=f),onClick:k(s).setCookiesForInputData},null,8,["modelValue","onClick"])]),_:1}),e(p,{cols:"1",class:"searchBtn"},{default:t(()=>[e(m,{type:"button",onClick:b,style:{"background-color":"#0275ff","padding-left":"25px","margin-left":"10px"}},{default:t(()=>[oe]),_:1})]),_:1}),e(p,{cols:"3"}),e(p,{cols:"3"})]),_:1})]),_:1})}}};const z=r=>(O("data-v-08ebc16d"),r=r(),j(),r),le={class:"outerdivWeightAndValue"},se=z(()=>l("div",{id:"weightAndValue",class:"button-style",style:{"background-color":"#5D2296",border:"none"}},"Total Weight and Value In a day",-1)),ae=z(()=>l("br",null,null,-1)),ne={class:"totalWandV"},ce={class:"inlineLayout"},re={class:"tablesRow",style:{"text-align":"center"}},de={class:"tableHeading"},ie=z(()=>l("tr",null,[l("th",{scope:"col"},"Total Value"),l("th",{scope:"col"},"Total Weight")],-1)),_e={__name:"DayWeightAndValue",props:{weightAndValue:Object},setup(r){let o=0;const s=["Mon","Tue","Wed","Thu","Fri","Sat","Sun"];return(b,d)=>{const a=u("b-col"),m=u("b-row");return _(),y("div",le,[se,ae,l("div",ne,[e(m,{class:"display-table-inline"},{default:t(()=>[(_(!0),y(P,null,F(r.weightAndValue,(p,g)=>(_(),C(a,{cols:"1",key:g},{default:t(()=>[l("div",ce,[l("table",re,[l("thead",null,[l("div",de,c(s[k(o)])+" - "+c(g),1),$(l("span",null,c(q(o)?o.value++:o++),513),[[L,!1]]),ie]),l("tbody",null,[l("tr",null,[l("td",null,"Post,coll: \xA3"+c(Math.floor(p.valuePostCollection*100)/100),1),l("td",null,"Post,coll: "+c(Math.floor(p.weightPostCollection*100)/100)+" Kg",1)]),l("tr",null,[l("td",null,"Courier: \xA3"+c(Math.floor(p.valueCourier*100)/100),1),l("td",null,"Courier: "+c(Math.floor(p.weightCourier*100)/100)+" Kg",1)]),l("tr",null,[l("td",null,"Total: \xA3"+c(Math.floor(p.value*100)/100),1),l("td",null,"Total: "+c(Math.floor(p.weight*100)/100)+" Kg",1)])])])])]),_:2},1024))),128)),$(l("span",null,c(q(o)?o.value=0:o=0),513),[[L,!1]])]),_:1})])])}}},ue=R(_e,[["__scopeId","data-v-08ebc16d"]]),U=J("table",{state:()=>({isBookableCheck:"",orders:"",updatedComments:"",bookableValue:"",date:"",vehicleNo:"",isCheck:Boolean,responseStatus:Object,bookableBackgroundColor:Boolean}),actions:{getComments(r,o,s){this.controller&&this.controller.abort(),this.controller=new AbortController;const b=this.controller.signal;console.log(r),console.log(s),H.post("/api/updatecomments",{updatedComments:r,selectedDate:o,selectedVehicleNo:s},{signal:b}).then(d=>{console.log(d.data)}).catch(function(d){d.name==="AbortError"&&console.log("Request canceled",d.name)})},updateBookableValue(r,o){let s=r+o;document.getElementById(s).getAttribute("aria-label")=="Bookable"?this.isBookableCheck=1:this.isBookableCheck=0,this.controller&&this.controller.abort(),this.controller=new AbortController;const a=this.controller.signal;H.post("/api/updatebookable",{bookableValue:this.isBookableCheck,selectedDate:r,vehicleNo:o},{signal:a}).then(m=>{m.data.error_msg==""&&m.data.updateStatus==1?window.location.reload():G.fire({title:"Bookable status not saved",text:"Error in Database",icon:"warning"})}).catch(function(m){m.name==="AbortError"&&console.log("Request canceled",m.name)})}}}),be=J({id:"PrintTable",state:()=>({tableIdToPrint:"",htmlMarkup:""}),actions:{printTable(r){console.log(r.comments),console.log(r);let o=[];for(let b in r)r[b].order_no!=null&&r[b].ship_to_post_code!=null&&r[b].ship_to_name!=null&&r[b].shipment_type!=null&&o.push({orderNo:r[b].order_no,name:r[b].ship_to_name,postCode:r[b].ship_to_post_code,Type:r[b].shipment_type,van:r[b].van_number});let s=window.open("","","height=950, width=1000");s.document.write("<html>"),s.document.write("<body > <h1>DAILY DELIVERY SHEET</h1> <br>"),s.document.write('<textarea style="resize: none;" rows="4" cols="106">'+r.comments+"</textarea>"),s.document.write("<br/><br/>"),s.document.write("Date : "+r.date+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Van : "+o[0].van),s.document.write("<br/><br/>"),s.document.write('<div style="border-collapse: collapse; width:800px;border: 1px solid black">'),s.document.write('<table style="border-collapse: collapse; width:800px;border: 1px solid black">'),s.document.write('<thead "><tr>'),s.document.write('<th style="border-collapse: collapse; border: 1px solid black; width:100px; scope="col">Invoice</th>'),s.document.write('<th style="border-collapse: collapse; border: 1px solid black; width:100px; scope="col">Name</th>'),s.document.write('<th style="border-collapse: collapse; border: 1px solid black; width:100px; scope="col">Post Code</th>'),s.document.write('<th style="border-collapse: collapse; border: 1px solid black; width:100px; scope="col">Comments</th>'),s.document.write('<th style="border-collapse: collapse; border: 1px solid black; width:100px; scope="col">Picker</th>'),s.document.write('<th style="border-collapse: collapse; border: 1px solid black; width:100px; scope="col">Lines</th>'),s.document.write('<th style="border-collapse: collapse; border: 1px solid black; width:100px; scope="col">Checked</th>'),s.document.write("</tr></thead>"),s.document.write('<tbody">');for(let b=0;b<o.length;b++)s.document.write('<tr style="text-align:center">'),s.document.write('<td style="border-collapse: collapse; border: 1px solid black; width:100px;height:100px;">'+o[b].orderNo+"</td>"),s.document.write('<td style="border-collapse: collapse; border: 1px solid black; width:100px">'+o[b].name+"</td>"),s.document.write('<td style="border-collapse: collapse; border: 1px solid black; width:100px">'+o[b].postCode+"</td>"),s.document.write('<td style="border-collapse: collapse; border: 1px solid black; width:100px">'+o[b].Type+"</td>"),s.document.write('<td style="border-collapse: collapse; border: 1px solid black; width:100px"> </td>'),s.document.write('<td style="border-collapse: collapse; border: 1px solid black; width:100px"> </td>'),s.document.write('<td style="border-collapse: collapse; border: 1px solid black; width:100px"> </td>'),s.document.write("</tr>");s.document.write("</tbody>"),s.document.write("</table>"),s.document.write("<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>"),s.document.write('<div style="border-collapse: collapse; width:800px;border: 1px solid black">'),s.document.write("<br/><br/><br/>"),s.document.write("&nbsp;&nbsp;&nbsp;&nbsp;Signed..........................................................................."),s.document.write("<br/><br/><br/>"),s.document.write("&nbsp;&nbsp;&nbsp;&nbsp;Date..........................................................................."),s.document.write("<br/><br/><br/>"),s.document.write("&nbsp;&nbsp;&nbsp;&nbsp;Time..........................................................................."),s.document.write("<br/><br/><br/>"),s.document.write("</div>"),s.document.write("</div>"),s.document.write("</body></html>"),s.document.close(),s.print()}}});const M=r=>(O("data-v-3230fe3b"),r=r(),j(),r),pe={class:"belowContentWrapper"},ye=M(()=>l("br",null,null,-1)),he=M(()=>l("br",null,null,-1)),me={style:{display:"flex"}},ge={key:0,class:"bookableClass"},fe={key:1,class:"bookableClass"},ke=V("Print"),we=M(()=>l("br",null,null,-1)),ve=M(()=>l("strong",null,[l("span",{style:{color:"red","padding-left":"18%"}},"Wrong API calculation in Database")],-1)),Ce=M(()=>l("strong",null," \xA0 ",-1)),De={__name:"tableDescriptionData",props:{order:Object,vehicleNo:String,targetAmount:String,deliveryCapacity:Number},setup(r){const o=r,s=U(),b=be(),d=()=>{b.printTable(o.order)},a=()=>{s.updateBookableValue(o.order.date,o.vehicleNo)};return(m,p)=>{const g=u("b-col"),f=u("b-row"),D=u("b-button");return _(),y("div",pe,[e(f,{class:"rowsize"},{default:t(()=>[e(g,{cols:"6",style:W({color:[o.order.value_booked<=.8*o.targetAmount?"#ff0000":o.order.value_booked>.8*o.targetAmount&&o.order.value_booked<o.targetAmount?"#FF8533":o.order.value_booked>o.targetAmount?"#009933":"#a89595"]})},{default:t(()=>[V(" Value Booked:\xA3 "+c(o.order.value_booked),1)]),_:1},8,["style"]),e(g,{cols:"6",style:W({color:[o.order.booked_weight<=.8*o.deliveryCapacity?"":o.order.booked_weight>.8*o.deliveryCapacity&&o.order.booked_weight<o.deliveryCapacity?"#FF8533":o.order.booked_weight>o.deliveryCapacity?"#ff0000":""]})},{default:t(()=>[V(" Booked Weight:"+c(o.order.booked_weight)+" Kg ",1)]),_:1},8,["style"])]),_:1}),ye,e(f,{class:"rowsize"},{default:t(()=>[e(g,{cols:"6",style:W({color:[o.order.value_booked<=.8*o.targetAmount?"#ff0000":o.order.value_booked>.8*o.targetAmount&&o.order.value_booked<o.targetAmount?"#FF8533":o.order.value_booked>o.targetAmount?"#009933":"#a89595"]})},{default:t(()=>[V(" To Book:\xA3 "+c(o.order.to_book),1)]),_:1},8,["style"]),e(g,{cols:"6",style:W({color:[o.order.booked_weight>o.deliveryCapacity?"#ff0000":o.order.booked_weight>.8*o.deliveryCapacity&&o.order.booked_weight<o.deliveryCapacity?"#FF8533":o.order.booked_weight<=.8*o.deliveryCapacity?"#000000":""]})},{default:t(()=>[V(" Remaining Weight:"+c(o.order.remaining_weight)+" Kg ",1)]),_:1},8,["style"])]),_:1}),he,e(f,{class:"rowsize"},{default:t(()=>[e(g,{cols:"6"},{default:t(()=>[l("div",me,[e(D,{class:I(["bookableClass",`${o.order.bookable_status==1?"notavailableBookable":"availableBookable"}`]),id:o.order.date+o.vehicleNo,"aria-label":`${o.order.bookable_status==1?"Not Bookable":"Bookable"}`,modelValue:o.order.bookable_status,"onUpdate:modelValue":p[0]||(p[0]=n=>o.order.bookable_status=n),type:"button",name:"bookable",onClick:a},{default:t(()=>[o.order.bookable_status==1?(_(),y("span",ge," Not Bookable ")):(_(),y("span",fe," Bookable "))]),_:1},8,["id","aria-label","modelValue","class"])])]),_:1}),e(g,{cols:"6"},{default:t(()=>[e(D,{variant:"outline-primary",onClick:d},{default:t(()=>[ke]),_:1})]),_:1})]),_:1}),we,o.order.travelTime==0&&o.order.journeyDistance==0&&o.order.value_booked!=0&&o.order.booked_weight!=0?(_(),C(f,{key:0,class:"rowsize"},{default:t(()=>[e(g,{cols:"12"},{default:t(()=>[ve]),_:1})]),_:1})):o.order.travelTime==0&&o.order.journeyDistance==0&&o.order.value_booked==0&&o.order.booked_weight==0?(_(),C(f,{key:1,class:"rowsize"},{default:t(()=>[e(g,{cols:"12"},{default:t(()=>[Ce]),_:1})]),_:1})):(_(),C(f,{key:2,class:"rowsize"},{default:t(()=>[e(g,{cols:"6"},{default:t(()=>[l("strong",null," Travel Time : "+c(o.order.travelTime),1)]),_:1}),e(g,{cols:"6"},{default:t(()=>[l("strong",null," Journey Distance: "+c(o.order.journeyDistance)+" miles ",1)]),_:1})]),_:1}))])}}},S=R(De,[["__scopeId","data-v-3230fe3b"]]);const Ae={class:"comments-row"},We={__name:"tableDescriptionComments",props:{order:Object,vehicleNo:String},setup(r){const o=r,s=U();return(b,d)=>{const a=u("FormKit"),m=u("b-col"),p=u("b-row");return _(),y("div",Ae,[e(p,null,{default:t(()=>[e(m,{cols:"12"},{default:t(()=>[e(a,{type:"textarea",label:"Comments",rows:"1",modelValue:o.order.comments,"onUpdate:modelValue":d[0]||(d[0]=g=>o.order.comments=g),onInput:d[1]||(d[1]=g=>k(s).getComments(o.order.comments,o.order.date,o.vehicleNo)),placeholder:"type here"},null,8,["modelValue"])]),_:1})]),_:1})])}}},x=R(We,[["__scopeId","data-v-84b9022e"]]);const Ve={class:"AllRunsWtValTotal"},Se={__name:"tableTotalRunDetails",props:{AllRunsWtvalPerDay:Object},setup(r){const o=r;return U(),(s,b)=>{const d=u("b-col"),a=u("b-row");return _(),y("div",Ve,[e(a,{style:{"background-color":"#B0C4DE",padding:"5px","border-radius":"2px"}},{default:t(()=>[e(d,{cols:"6"},{default:t(()=>[l("strong",null,"Total Value: \xA3 "+c(o.AllRunsWtvalPerDay.totalDayValue),1)]),_:1}),e(d,{cols:"6"},{default:t(()=>[l("strong",null,"Total Wt.: "+c(o.AllRunsWtvalPerDay.totalDayWeight)+"Kg",1)]),_:1})]),_:1}),e(a,{style:{"background-color":"#B0C4DE",padding:"2px","border-radius":"2px"}},{default:t(()=>[e(d,{cols:"6"},{default:t(()=>[l("strong",null,"Total Time: "+c(o.AllRunsWtvalPerDay.totalTravelTime),1)]),_:1}),e(d,{cols:"6"},{default:t(()=>[l("strong",null,"Total Mileage: "+c(o.AllRunsWtvalPerDay.totalJourneyDistance)+" mi",1)]),_:1})]),_:1})])}}},T=R(Se,[["__scopeId","data-v-b77c7483"]]),xe={class:"inlineLayout"},Te={class:"tableHeading"},$e={class:"table tablesRow table-bordered"},Be=l("thead",null,[l("tr",null,[l("th",{scope:"col"},"Order no."),l("th",{scope:"col"},"Name"),l("th",{scope:"col"},"Postcode"),l("th",{scope:"col"},"Area"),l("th",{scope:"col"},"Items"),l("th",{scope:"col"},"Wt."),l("th",{scope:"col"},"\xA3"),l("th",{scope:"col"},"Type")])],-1),Fe={key:0},Pe=l("br",null,null,-1),Re=l("strong",null,"Shipped",-1),Me=[Pe,Re],Ee={key:1},Ie=l("br",null,null,-1),Ke=[Ie],Oe={key:2},je=l("br",null,null,-1),Ne=l("strong",null,"Pt Shipped",-1),ze=[je,Ne],Ue={key:3},qe=l("br",null,null,-1),Le=[qe],He={key:0},Je=l("strong",null,"Paid",-1),Ye=[Je],Ge={key:1},Qe=l("br",null,null,-1),Xe=[Qe],Ze={key:2},eo=l("strong",null,"Paid",-1),oo=[eo],to={key:3},lo=l("br",null,null,-1),so=[lo],h={__name:"table",props:{order:Object,weekDay:String,vehicleNo:String},setup(r){const o=r;return(s,b)=>(_(),y("div",xe,[l("div",Te,c(o.weekDay)+"-"+c(o.order.date),1),l("table",$e,[Be,(_(!0),y(P,null,F(o.order,(d,a)=>(_(),y("tbody",{key:d},[a!="value_booked"&&a!="to_book"&&a!="booked_weight"&&a!="remaining_weight"&&a!="comments"&&a!="date"&&a!="bookable_status"&&a!="journeyDistance"&&a!="travelTime"&&a!="display_order_position"&&a!="orginalSequence"?(_(),y("tr",{key:0,style:W([{background:d.balance_amount>0&&d.delivery_confirmed==1?"#FFC671":d.balance_amount<=0&&d.delivery_confirmed==1?"#D7F6C8":d.delivery_confirmed==0&&d.balance_amount>=0&&d.dispatch_requested_date!="1970-01-01 00:00:00"&&d.dispatch_requested_date!="1753-01-01 00:00:00"?"#ffb3ff":"#F9F3E0"}])},[l("td",null,[V(c(d.order_no)+" ",1),d.ship_status=="Shipped"?(_(),y("span",Fe,Me)):(_(),y("span",Ee,Ke)),d.ship_status=="Part Shipped"?(_(),y("span",Oe,ze)):(_(),y("span",Ue,Le))]),l("td",null,c(d.ship_to_name),1),l("td",null,c(d.ship_to_post_code),1),l("td",null,c(d.ship_to_city),1),l("td",null,c(d.type_of_supply_code),1),l("td",null,c(d.order_weight)+" Kg",1),l("td",null,[V("\xA3"+c(d.order_amount)+" ",1),d.balance_amount<=0&&d.delivery_confirmed==0?(_(),y("span",He,Ye)):(_(),y("span",Ge,Xe)),d.balance_amount<=0&&d.delivery_confirmed==1?(_(),y("span",Ze,oo)):(_(),y("span",to,so))]),l("td",null,c(d.shipment_type),1)],4)):N("",!0)]))),128))])]))}};const Y=r=>(O("data-v-263360ea"),r=r(),j(),r),ao={class:"accordion accordiansDisplay",role:"tablist"},no={class:"vanDetails",style:W([{color:"#800000"}])},co={class:"vanDetails2",style:W([{color:"#800000"}])},ro={key:0},io={id:"accordion"},_o={class:"card"},uo=["id","data-bs-target","aria-bs-controls","onClick"],bo={class:"vanDetails"},po={class:"vanDetails2"},yo=["id","aria-bs-labelledby"],ho={class:"card-body"},mo=Y(()=>l("br",null,null,-1)),go=Y(()=>l("br",null,null,-1)),fo={__name:"VehicalsAccordian",setup(r){const o=B();return(s,b)=>{const d=u("b-button"),a=u("b-col"),m=u("b-row"),p=u("b-collapse"),g=u("b-container"),f=u("b-card"),D=E("b-toggle");return _(),y("div",ao,[(_(!0),y(P,null,F(k(o).selectedData,(n,w)=>(_(),C(f,{"no-body":"",class:"mb-1",key:w+"1"},{default:t(()=>[$((_(),C(d,{id:w,class:"button-style",style:W([{background:n.vehicle_type=="3.5T Flat Bed"?"#E1E8FB":n.vehicle_type=="3.5T Box Van"?"#F5E7F0":n.vehicle_type=="3.5T Panel Van"?"#ECECEC":n.vehicle_type=="7.5T Flat Bed"?"#FFFFCC":n.vehicle_type=="18T Flat Bed"?"#F9DAA8":n.vehicle_type=="18T Curtain Side"?"#ECECEC":""},{border:s.none}]),onClick:v=>k(o).ParentCollapseHideOrShow(w)},{default:t(()=>[l("strong",no,c(w+" | ")+" "+c(n.registration_number+" | ")+" "+c(n.delivery_capacity+" Kg | ")+" "+c(n.vehicle_type),1),l("strong",co,c(w+" | ")+" "+c(n.registration_number+" | ")+" "+c(n.delivery_capacity+" Kg | ")+" "+c(n.vehicle_type),1)]),_:2},1032,["id","style","onClick"])),[[D,w+"1"]]),e(g,{fluid:""},{default:t(()=>[e(p,{id:w+"1",class:I(n.parent_collapse_status===1?"show":""),accordion:"dd",role:"tabpanel"},{default:t(()=>[(_(!0),y(P,null,F(n,(v,i)=>(_(),y("span",{key:i},[i!=w&&i!="delivery_capacity"&&i!="target_amount"&&i!="parent_collapse_status"&&i!="vehicle_type"&&i!="registration_number"&&i!="totalDayWtAndValue"?(_(),y("span",ro,[l("div",io,[l("div",_o,[l("button",{style:W([{background:n.vehicle_type=="3.5T Flat Bed"?"#E1E8FB":n.vehicle_type=="3.5T Box Van"?"#F5E7F0":n.vehicle_type=="3.5T Panel Van"?"#ECECEC":n.vehicle_type=="7.5T Flat Bed"?"#FFFFCC":n.vehicle_type=="18T Flat Bed"?"#F9DAA8":n.vehicle_type=="18T Curtain Side"?"#ECECEC":""},{"border-style":"ridge"},{height:"35px"},{color:"#5D2296"},{"border-radius":"20px"}]),id:i.includes("RUN")?"#"+i:"#"+i+"RUN",class:"VehicleHeading","data-bs-toggle":"collapse","data-bs-target":i.includes("RUN")?"#"+i:"#"+i+"RUN","aria-bs-expanded":"false","aria-bs-controls":i,onClick:jo=>k(o).hideShowVanRun(i)},[l("strong",bo,c(i)+" - Value: \xA3 "+c(v.totalValue)+" | Weight:"+c(v.totalWeight+" kg"),1),l("strong",po,c(i)+" - Value: \xA3 "+c(v.totalValue)+" | Weight:"+c(v.totalWeight+" kg"),1)],12,uo),l("div",{id:i.includes("RUN")?i:i+"RUN",class:I(["collapse",v.hideOrShowVehicle===1?"show":""]),"aria-bs-labelledby":i,"data-bs-parent":"#accordion"},[l("div",ho,[e(m,{class:"display-table-inline"},{default:t(()=>[e(a,{cols:"1"},{default:t(()=>[e(h,{order:v.Monday,"week-day":"Monday","vehicle-no":w},null,8,["order","vehicle-no"])]),_:2},1024),e(a,{cols:"1"},{default:t(()=>[e(h,{order:v.Tuesday,"week-day":"Tuesday","vehicle-no":w},null,8,["order","vehicle-no"])]),_:2},1024),e(a,{cols:"1"},{default:t(()=>[e(h,{order:v.Wednesday,"week-day":"Wednesday","vehicle-no":w},null,8,["order","vehicle-no"])]),_:2},1024),e(a,{cols:"1"},{default:t(()=>[e(h,{order:v.Thursday,"week-day":"Thursday","vehicle-no":w},null,8,["order","vehicle-no"])]),_:2},1024),e(a,{cols:"1"},{default:t(()=>[e(h,{order:v.Friday,"week-day":"Friday","vehicle-no":w},null,8,["order","vehicle-no"])]),_:2},1024),e(a,{cols:"1"},{default:t(()=>[e(h,{order:v.Saturday,"week-day":"Saturday","vehicle-no":w},null,8,["order","vehicle-no"])]),_:2},1024),e(a,{cols:"1"},{default:t(()=>[e(h,{order:v.Sunday,"week-day":"Sunday","vehicle-no":w},null,8,["order","vehicle-no"])]),_:2},1024),e(a,{cols:"5"})]),_:2},1024),mo,e(m,{id:"belowLayoutofTables"},{default:t(()=>[e(a,{cols:"1",class:"description"},{default:t(()=>[e(S,{order:n[i].Monday,"vehicle-no":i,targetAmount:n.target_amount,deliveryCapacity:n.delivery_capacity},null,8,["order","vehicle-no","targetAmount","deliveryCapacity"])]),_:2},1024),e(a,{cols:"1",class:"description"},{default:t(()=>[e(S,{order:n[i].Tuesday,"vehicle-no":i,targetAmount:n.target_amount,deliveryCapacity:n.delivery_capacity},null,8,["order","vehicle-no","targetAmount","deliveryCapacity"])]),_:2},1024),e(a,{cols:"1",class:"description"},{default:t(()=>[e(S,{order:n[i].Wednesday,"vehicle-no":i,targetAmount:n.target_amount,deliveryCapacity:n.delivery_capacity},null,8,["order","vehicle-no","targetAmount","deliveryCapacity"])]),_:2},1024),e(a,{cols:"1",class:"description"},{default:t(()=>[e(S,{order:n[i].Thursday,"vehicle-no":i,targetAmount:n.target_amount,deliveryCapacity:n.delivery_capacity},null,8,["order","vehicle-no","targetAmount","deliveryCapacity"])]),_:2},1024),e(a,{cols:"1",class:"description"},{default:t(()=>[e(S,{order:n[i].Friday,"vehicle-no":i,targetAmount:n.target_amount,deliveryCapacity:n.delivery_capacity},null,8,["order","vehicle-no","targetAmount","deliveryCapacity"])]),_:2},1024),e(a,{cols:"1",class:"description"},{default:t(()=>[e(S,{order:n[i].Saturday,"vehicle-no":i,targetAmount:n.target_amount,deliveryCapacity:n.delivery_capacity},null,8,["order","vehicle-no","targetAmount","deliveryCapacity"])]),_:2},1024),e(a,{cols:"1",class:"description"},{default:t(()=>[e(S,{order:n[i].Sunday,"vehicle-no":i,targetAmount:n.target_amount,deliveryCapacity:n.delivery_capacity},null,8,["order","vehicle-no","targetAmount","deliveryCapacity"])]),_:2},1024),e(a,{cols:"5"})]),_:2},1024),go,e(m,{id:"belowLayoutofTableComments"},{default:t(()=>[e(a,{cols:"1",class:"tableComments"},{default:t(()=>[e(x,{order:n[i].Monday,"vehicle-no":i,AllRunsWtvalPerDay:n.totalDayWtAndValue.Monday},null,8,["order","vehicle-no","AllRunsWtvalPerDay"])]),_:2},1024),e(a,{cols:"1",class:"tableComments"},{default:t(()=>[e(x,{order:n[i].Tuesday,"vehicle-no":i,AllRunsWtvalPerDay:n.totalDayWtAndValue.Tuesday},null,8,["order","vehicle-no","AllRunsWtvalPerDay"])]),_:2},1024),e(a,{cols:"1",class:"tableComments"},{default:t(()=>[e(x,{order:n[i].Wednesday,"vehicle-no":i,AllRunsWtvalPerDay:n.totalDayWtAndValue.Wednesday},null,8,["order","vehicle-no","AllRunsWtvalPerDay"])]),_:2},1024),e(a,{cols:"1",class:"tableComments"},{default:t(()=>[e(x,{order:n[i].Thursday,"vehicle-no":i,AllRunsWtvalPerDay:n.totalDayWtAndValue.Thursday},null,8,["order","vehicle-no","AllRunsWtvalPerDay"])]),_:2},1024),e(a,{cols:"1",class:"tableComments"},{default:t(()=>[e(x,{order:n[i].Friday,"vehicle-no":i,AllRunsWtvalPerDay:n.totalDayWtAndValue.Friday},null,8,["order","vehicle-no","AllRunsWtvalPerDay"])]),_:2},1024),e(a,{cols:"1",class:"tableComments"},{default:t(()=>[e(x,{order:n[i].Saturday,"vehicle-no":i,AllRunsWtvalPerDay:n.totalDayWtAndValue.Saturday},null,8,["order","vehicle-no","AllRunsWtvalPerDay"])]),_:2},1024),e(a,{cols:"1",class:"tableComments"},{default:t(()=>[e(x,{order:n[i].Saturday,"vehicle-no":i,AllRunsWtvalPerDay:n.totalDayWtAndValue.Saturday},null,8,["order","vehicle-no","AllRunsWtvalPerDay"])]),_:2},1024)]),_:2},1024)])],10,yo)])])])):N("",!0)]))),128)),e(m,{id:"belowLayoutofTableComments"},{default:t(()=>[e(a,{cols:"1",class:"tableComments"},{default:t(()=>[e(T,{AllRunsWtvalPerDay:n.totalDayWtAndValue.Monday},null,8,["AllRunsWtvalPerDay"])]),_:2},1024),e(a,{cols:"1",class:"tableComments"},{default:t(()=>[e(T,{AllRunsWtvalPerDay:n.totalDayWtAndValue.Tuesday},null,8,["AllRunsWtvalPerDay"])]),_:2},1024),e(a,{cols:"1",class:"tableComments"},{default:t(()=>[e(T,{AllRunsWtvalPerDay:n.totalDayWtAndValue.Wednesday},null,8,["AllRunsWtvalPerDay"])]),_:2},1024),e(a,{cols:"1",class:"tableComments"},{default:t(()=>[e(T,{AllRunsWtvalPerDay:n.totalDayWtAndValue.Thursday},null,8,["AllRunsWtvalPerDay"])]),_:2},1024),e(a,{cols:"1",class:"tableComments"},{default:t(()=>[e(T,{AllRunsWtvalPerDay:n.totalDayWtAndValue.Friday},null,8,["AllRunsWtvalPerDay"])]),_:2},1024),e(a,{cols:"1",class:"tableComments"},{default:t(()=>[e(T,{AllRunsWtvalPerDay:n.totalDayWtAndValue.Saturday},null,8,["AllRunsWtvalPerDay"])]),_:2},1024),e(a,{cols:"1",class:"tableComments"},{default:t(()=>[e(T,{AllRunsWtvalPerDay:n.totalDayWtAndValue.Saturday},null,8,["AllRunsWtvalPerDay"])]),_:2},1024)]),_:2},1024)]),_:2},1032,["id","class"])]),_:2},1024)]),_:2},1024))),128))])}}},ko=R(fo,[["__scopeId","data-v-263360ea"]]),wo={class:"accordion",role:"tablist"},vo={class:"vanDetails"},Co={class:"vanDetails2"},Do={__name:"Collections",props:{collections:Object},setup(r){const o=r;return B(),(s,b)=>{const d=u("b-button"),a=u("b-col"),m=u("b-row"),p=u("b-card-body"),g=u("b-collapse"),f=u("b-container"),D=u("b-card"),n=E("b-toggle");return _(),y("div",wo,[e(D,{"no-body":"",class:"mb-1"},{default:t(()=>[$((_(),C(d,{class:"button-style",block:"",style:{"background-color":"#5D2296",border:"none"}},{default:t(()=>[l("strong",vo," Collections - Value: \xA3 "+c(Math.floor(o.collections.collectionValueBooked*100)/100)+" | Weight: "+c(Math.floor(o.collections.collectionBookedWeight*100)/100)+" Kg ",1),l("strong",Co," Collections - Value: \xA3 "+c(Math.floor(o.collections.collectionValueBooked*100)/100)+" | Weight: "+c(Math.floor(o.collections.collectionBookedWeight*100)/100)+" Kg ",1)]),_:1})),[[n,void 0,void 0,{collectionAccordian:!0}]]),e(f,{fluid:"",style:{"margin-left":"-14px"}},{default:t(()=>[e(g,{id:"collectionAccordian",accordion:"collectionAccordian",role:"tabpanel"},{default:t(()=>[e(p,{class:"Tables-card-body"},{default:t(()=>[e(m,{class:"display-table-inline"},{default:t(()=>[e(a,{cols:"1"},{default:t(()=>[e(h,{order:o.collections.Monday,"week-day":"Monday"},null,8,["order"])]),_:1}),e(a,{cols:"1"},{default:t(()=>[e(h,{order:o.collections.Tuesday,"week-day":"Tuesday"},null,8,["order"])]),_:1}),e(a,{cols:"1"},{default:t(()=>[e(h,{order:o.collections.Wednesday,"week-day":"Wednesday"},null,8,["order"])]),_:1}),e(a,{cols:"1"},{default:t(()=>[e(h,{order:o.collections.Thursday,"week-day":"Thursday"},null,8,["order"])]),_:1}),e(a,{cols:"1"},{default:t(()=>[e(h,{order:o.collections.Friday,"week-day":"Friday"},null,8,["order"])]),_:1}),e(a,{cols:"1"},{default:t(()=>[e(h,{order:o.collections.Saturday,"week-day":"Saturday"},null,8,["order"])]),_:1}),e(a,{cols:"1"},{default:t(()=>[e(h,{order:o.collections.Sunday,"week-day":"Sunday"},null,8,["order"])]),_:1})]),_:1})]),_:1})]),_:1})]),_:1})]),_:1})])}}},Ao={class:"accordion",role:"tablist"},Wo={class:"vanDetails"},Vo={class:"vanDetails2"},So={__name:"Post",props:{post:Object},setup(r){const o=r;return B(),(s,b)=>{const d=u("b-button"),a=u("b-col"),m=u("b-row"),p=u("b-card-body"),g=u("b-collapse"),f=u("b-container"),D=u("b-card"),n=E("b-toggle");return _(),y("div",Ao,[e(D,{"no-body":"",class:"mb-1"},{default:t(()=>[$((_(),C(d,{class:"button-style",block:"",style:{"background-color":"#5D2296",border:"none"}},{default:t(()=>[l("strong",Wo," Post - Value: \xA3 "+c(Math.floor(o.post.postValueBooked*100)/100)+" | Weight: "+c(Math.floor(o.post.postBookedWeight*100)/100)+" Kg ",1),l("strong",Vo," Post - Value: \xA3 "+c(Math.floor(o.post.postValueBooked*100)/100)+" | Weight: "+c(Math.floor(o.post.postBookedWeight*100)/100)+" Kg ",1)]),_:1})),[[n,void 0,void 0,{postAccordian:!0}]]),e(f,{fluid:"",style:{"margin-left":"-14px"}},{default:t(()=>[e(g,{id:"postAccordian",accordion:"postAccordian",role:"tabpanel"},{default:t(()=>[e(p,{class:"Tables-card-body"},{default:t(()=>[e(m,{class:"display-table-inline"},{default:t(()=>[e(a,{cols:"1"},{default:t(()=>[e(h,{order:o.post.Monday,"week-day":"Monday"},null,8,["order"])]),_:1}),e(a,{cols:"1"},{default:t(()=>[e(h,{order:o.post.Tuesday,"week-day":"Tuesday"},null,8,["order"])]),_:1}),e(a,{cols:"1"},{default:t(()=>[e(h,{order:o.post.Wednesday,"week-day":"Wednesday"},null,8,["order"])]),_:1}),e(a,{cols:"1"},{default:t(()=>[e(h,{order:o.post.Thursday,"week-day":"Thursday"},null,8,["order"])]),_:1}),e(a,{cols:"1"},{default:t(()=>[e(h,{order:o.post.Friday,"week-day":"Friday"},null,8,["order"])]),_:1}),e(a,{cols:"1"},{default:t(()=>[e(h,{order:o.post.Saturday,"week-day":"Saturday"},null,8,["order"])]),_:1}),e(a,{cols:"1"},{default:t(()=>[e(h,{order:o.post.Sunday,"week-day":"Sunday"},null,8,["order"])]),_:1})]),_:1})]),_:1})]),_:1})]),_:1})]),_:1})])}}},xo={class:"accordion",role:"tablist"},To={class:"vanDetails"},$o={class:"vanDetails2"},Bo={__name:"Courier",props:{courier:Object},setup(r){const o=r,s=K(),b=B();return s.selectedBranch=A.getCookie("selected_branch"),(d,a)=>{const m=u("b-button"),p=u("b-col"),g=u("b-row"),f=u("b-card-body"),D=u("b-collapse"),n=u("b-container"),w=u("b-card"),v=E("b-toggle");return _(),y("div",xo,[e(w,{"no-body":"",class:"mb-1"},{default:t(()=>[$((_(),C(m,{class:"button-style",block:"",style:{"background-color":"#5D2296",border:"none"}},{default:t(()=>[l("strong",To," Courier - Value: \xA3 "+c(Math.floor(o.courier.courierValueBooked*100)/100)+" | Weight: "+c(Math.floor(o.courier.courierBookedWeight*100)/100)+" Kg ",1),l("strong",$o," Courier - Value: \xA3 "+c(Math.floor(o.courier.courierValueBooked*100)/100)+" | Weight: "+c(Math.floor(o.courier.courierBookedWeight*100)/100)+" Kg ",1)]),_:1})),[[v,void 0,void 0,{courierAccordian:!0}]]),e(n,{fluid:"",style:{"margin-left":"-14px"}},{default:t(()=>[e(D,{id:"courierAccordian",visible:k(b).courierGosbertonStatus==!0,accordion:"courierAccordian",role:"tabpanel"},{default:t(()=>[e(f,{class:"Tables-card-body"},{default:t(()=>[e(g,{class:"display-table-inline"},{default:t(()=>[e(p,{cols:"1"},{default:t(()=>[e(h,{order:o.courier.Monday,"week-day":"Monday"},null,8,["order"])]),_:1}),e(p,{cols:"1"},{default:t(()=>[e(h,{order:o.courier.Tuesday,"week-day":"Tuesday"},null,8,["order"])]),_:1}),e(p,{cols:"1"},{default:t(()=>[e(h,{order:o.courier.Wednesday,"week-day":"Wednesday"},null,8,["order"])]),_:1}),e(p,{cols:"1"},{default:t(()=>[e(h,{order:o.courier.Thursday,"week-day":"Thursday"},null,8,["order"])]),_:1}),e(p,{cols:"1"},{default:t(()=>[e(h,{order:o.courier.Friday,"week-day":"Friday"},null,8,["order"])]),_:1}),e(p,{cols:"1"},{default:t(()=>[e(h,{order:o.courier.Saturday,"week-day":"Saturday"},null,8,["order"])]),_:1}),e(p,{cols:"1"},{default:t(()=>[e(h,{order:o.courier.Sunday,"week-day":"Sunday"},null,8,["order"])]),_:1})]),_:1})]),_:1})]),_:1},8,["visible"])]),_:1})]),_:1})])}}},Fo=l("br",null,null,-1),Po=l("br",null,null,-1),Ro=l("br",null,null,-1),Mo=l("br",null,null,-1),Eo=l("br",null,null,-1),Io=l("br",null,null,-1),Ko=l("br",null,null,-1),Oo=l("br",null,null,-1),qo={__name:"dashboard",setup(r){const o=K(),s=Q(),b=B();return new Date().toISOString().slice(0,10),o.selectedBranch=A.getCookie("selected_branch"),o.selectedDate=A.getCookie("selected_date"),s.firstResponseCheck==!0?(o.selectedBranch=A.getCookie("selected_branch"),o.selectedDate=A.getCookie("selected_date"),b.SearchRequestedData(o.selectedBranch,o.selectedDate)):(s.firstResponseCheck=!0,b.firstRes=!0),(d,a)=>(_(),y("div",null,[e(X),e(te),Fo,e(Do,{collections:k(b).getCollections},null,8,["collections"]),e(So,{post:k(b).getPost},null,8,["post"]),e(Bo,{courier:k(b).getCourier},null,8,["courier"]),k(o).selectedBranch!=9?(_(),C(ko,{key:0})):N("",!0),e(ue,{weightAndValue:k(b).getWeightAndValueInAday},null,8,["weightAndValue"]),Po,Ro,Mo,e(Z),Eo,Io,Ko,Oo]))}};export{qo as default};