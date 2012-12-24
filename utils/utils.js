function limitTextarea(field, countfield, maxlimit) {
	if (field.value.length > maxlimit)
		field.value = field.value.substring(0, maxlimit);
	else 
		countfield.value = maxlimit - field.value.length;
}
//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////
var NMIN=3 ;
var NMAX=5;
var row_no=1; 
function addRow(tbl,row){ 
    if(row_no<NMAX){    
	    var textbox='<input type="text" name="next'+row_no+'">';//for text box
    	var remove= '<a href="#" onclick="removeRow(\''+ tbl +'\',\'' + row_no + '\')"/>Remove '+row_no+'</a>';

    var tbl = document.getElementById(tbl);//to identify the table in which the row will get insert 
    var rowIndex = document.getElementById(row).value;//to identify the row after which the row wil
    
    try { 
        var newRow = tbl.insertRow(row_no);//creation of new row 
        var newCell = newRow.insertCell(0);//first  cell in the row 
        newCell.innerHTML = "link:";//insertion of the 'text' variable in first cell 
        var newCell = newRow.insertCell(1);//second cell in the row 
        newCell.innerHTML = textbox + " " + remove;
        var newCell = newRow.insertCell(2);//second cell in the row
        newCell.innerHTML = document.getElementById("selectlink").value;
        
        row_no++; 
    } catch (ex) { 
        alert(ex); //if exception occurs 
    } 
        
} 
if(row_no>NMAX) {
    document.getElementById("add").style.display="none"; 
}                        
} 
function removeRow(tbl,num) { 
var table = document.getElementById(tbl);//adentification of table 
if(row_no>=NMIN) {
try { 
    row_no--; 
    table.deleteRow(num);//deletion of the clicked row 
} catch (ex) { 
    alert(ex); 
}
}

if(row_no<=NMAX) { //if row is less than 3 then the button will again appear to add row 
   document.getElementById("add").style.display="block"; 
}    
} 