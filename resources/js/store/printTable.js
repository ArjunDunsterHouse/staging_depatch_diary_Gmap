import { defineStore } from 'pinia'
//import routes from '../router/index.js';
//import Swal from 'sweetalert2';


export const PrintTableData = defineStore(
      {
            id:  'PrintTable',

      state: () => ({
           
            tableIdToPrint:'',
            htmlMarkup:'',
         
      }),

      actions:{

            printTable(orderTable){

                  console.log(orderTable.comments);
                  console.log(orderTable);

                  let array = [];
                  for (let index in orderTable) {

                        if(orderTable[index].order_no != undefined && orderTable[index].ship_to_post_code != undefined && orderTable[index].ship_to_name != undefined && orderTable[index].shipment_type != undefined)
                        {     
                              array.push  ({
                                    orderNo     :   orderTable[index].order_no,
                                    name        :   orderTable[index].ship_to_name,
                                    postCode    :   orderTable[index].ship_to_post_code,
                                    Type        :   orderTable[index].shipment_type,
                                    van         :   orderTable[index].van_number,
                              //      comments    :

                              })
                        }                       
                  }

                  // console.log("printing array in print table");
                  // console.log(array);

                 

            let print = window.open('', '', 'height=950, width=1000');
            print.document.write('<html>');
            print.document.write('<body > <h1>DAILY DELIVERY SHEET</h1> <br>');
            print.document.write('<textarea style="resize: none;" rows="4" cols="106">'+orderTable.comments+'</textarea>');
            print.document.write('<br/><br/>');
            print.document.write('Date : ' + orderTable.date+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Van : '+array[0].van);
            print.document.write('<br/><br/>');
            print.document.write('<div style="border-collapse: collapse; width:800px;border: 1px solid black">');
            print.document.write('<table style="border-collapse: collapse; width:800px;border: 1px solid black">');
            print.document.write('<thead "><tr>');
            print.document.write('<th style="border-collapse: collapse; border: 1px solid black; width:100px; scope="col">Invoice</th>');
            print.document.write('<th style="border-collapse: collapse; border: 1px solid black; width:100px; scope="col">Name</th>');
            print.document.write('<th style="border-collapse: collapse; border: 1px solid black; width:100px; scope="col">Post Code</th>');
            print.document.write('<th style="border-collapse: collapse; border: 1px solid black; width:100px; scope="col">Comments</th>');
            print.document.write('<th style="border-collapse: collapse; border: 1px solid black; width:100px; scope="col">Picker</th>');
            print.document.write('<th style="border-collapse: collapse; border: 1px solid black; width:100px; scope="col">Lines</th>');
            print.document.write('<th style="border-collapse: collapse; border: 1px solid black; width:100px; scope="col">Checked</th>');
            print.document.write('</tr></thead>');
            print.document.write('<tbody">');
        
            for(let i=0;i<array.length;i++){

                  print.document.write('<tr style="text-align:center">');
                  print.document.write('<td style="border-collapse: collapse; border: 1px solid black; width:100px;height:100px;">'+array[i].orderNo+'</td>');
                  print.document.write('<td style="border-collapse: collapse; border: 1px solid black; width:100px">'+array[i].name+'</td>');
                  print.document.write('<td style="border-collapse: collapse; border: 1px solid black; width:100px">'+array[i].postCode+'</td>');
                  print.document.write('<td style="border-collapse: collapse; border: 1px solid black; width:100px">'+array[i].Type+'</td>');
                  print.document.write('<td style="border-collapse: collapse; border: 1px solid black; width:100px"> </td>');
                  print.document.write('<td style="border-collapse: collapse; border: 1px solid black; width:100px"> </td>');
                  print.document.write('<td style="border-collapse: collapse; border: 1px solid black; width:100px"> </td>');
                  print.document.write('</tr>');
            }
            print.document.write('</tbody>');
            print.document.write('</table>');
            print.document.write('<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>');
            print.document.write('<div style="border-collapse: collapse; width:800px;border: 1px solid black">');
            print.document.write('<br/><br/><br/>');
            print.document.write('&nbsp;&nbsp;&nbsp;&nbsp;Signed...........................................................................');
            print.document.write('<br/><br/><br/>');
            print.document.write('&nbsp;&nbsp;&nbsp;&nbsp;Date...........................................................................');
            print.document.write('<br/><br/><br/>');
            print.document.write('&nbsp;&nbsp;&nbsp;&nbsp;Time...........................................................................');            
            print.document.write('<br/><br/><br/>');
            print.document.write('</div>');
            print.document.write('</div>');
            print.document.write('</body></html>');
            print.document.close();
            print.print();

            }
       },
  
})
