(function($) {
/*
 * Function: fnGetColumnData
 * Purpose:  Return an array of table values from a particular column.
 * Returns:  array string: 1d data array
 * Inputs:   object:oSettings - dataTable settings object. This is always the last argument past to the function
 *           int:iColumn - the id of the column to extract the data from
 *           bool:bUnique - optional - if set to false duplicated values are not filtered out
 *           bool:bFiltered - optional - if set to false all the table data is used (not only the filtered)
 *           bool:bIgnoreEmpty - optional - if set to false empty values are not filtered from the result array
 * Author:   Benedikt Forchhammer <b.forchhammer /AT\ mind2.de>
 */
$.fn.dataTableExt.oApi.fnGetColumnData = function ( oSettings, iColumn, bUnique, bFiltered, bIgnoreEmpty ) {
    if ( typeof iColumn == "undefined" ) return new Array();
    if ( typeof bUnique == "undefined" ) bUnique = true;
    if ( typeof bFiltered == "undefined" ) bFiltered = true;
    if ( typeof bIgnoreEmpty == "undefined" ) bIgnoreEmpty = true;
    var aiRows;
    if (bFiltered == true) aiRows = oSettings.aiDisplay;
    else aiRows = oSettings.aiDisplayMaster;
    var asResultData = new Array();
     
    for (var i=0,c=aiRows.length; i<c; i++) {
        iRow = aiRows[i];
        var aData = this.fnGetData(iRow);
        var sValue = aData[iColumn];
        if (bIgnoreEmpty == true && sValue.length == 0) continue;
        else if (bUnique == true && jQuery.inArray(sValue, asResultData) > -1) continue;
        else asResultData.push(sValue);
    }     
    return asResultData;
}}(jQuery));	
	

function fnCreateSelect( aData ) {
    var r='<select style="width:170px"><option value=""></option>', i, iLen=aData.length;
    for ( i=0 ; i<iLen ; i++ ) { r += '<option value="'+aData[i]+'">'+aData[i]+'</option>'; }
    return r+'</select>';
}