// Toggle classes and init sorting
$(".compare-feature").click(function(){
	// if it was unset
	if (!$(this).hasClass("sorted-down") && !$(this).hasClass("sorted-up")) {
		$(".compare-feature").removeClass("sorted-up").removeClass("sorted-down");
		$(this).addClass("sorted-up");
		var sortVector = 1;
	}
	// if it was set to UP
	else if ($(this).hasClass("sorted-up")) {
		$(".compare-feature").removeClass("sorted-up").removeClass("sorted-down");
		$(this).addClass("sorted-down");
		var sortVector = 2;
	}
	// if it was set DOWN
	else if ($(this).hasClass("sorted-down")) {
		$(".compare-feature").removeClass("sorted-up").removeClass("sorted-down");
		var sortVector = 0;
	};

	// get target column of the sorting
	var sortIndex = $(".compare-feature").index($(this));

	// if asket not to sort, sort by first column
	if (sortVector == 0) {
		console.log("sorting by 1st column");
		sortVector = 1;
		sortIndex = 0;
	}


	// init sorting
	sortColumn(sortIndex, sortVector);
});

function sortColumn(sortIndex, sortVector) {

	// harvest all rows
	var rows = $(".compare-item");

	// get index column values
	var indexVals = new Array();

	for (var i = 0; i < rows.length; i++) {
		indexVals[i] = new Array();
		indexVals[i][0] = rows.eq(i).children(".cell").eq(sortIndex+1).html();
		indexVals[i][1] = i;
	};


	// sort array by sub values
	indexVals = indexVals.sort(function(a,b) {
		// if 1st character is number AND string contains letters, remove all non-digits
		if (!isNaN(a[0][0]) && a[0].match(/[^\d.]/g)) {
			tempA = a[0];
			pos = tempA.search(/[^\d." "]/g); // if the number contains dot or space, don't let that stop us
			nums = tempA.substring(0, pos);
			tempA = nums.replace(/[^\d.]/g, "");
		}
		else {
			tempA = a[0];
		}
		// if 1st character is number AND string contains letters, remove all non-digits
		if (!isNaN(b[0][0]) && b[0].match(/[^\d.]/g)) {
			tempB = b[0];
			pos = tempB.search(/[^\d." "]/g); // if the number contains dot or space, don't let that stop us
			nums = tempB.substring(0, pos);
			tempB = nums.replace(/[^\d.]/g, "");
		}
		else {
			tempB = b[0];
		}

		// sort numerically is any of the 2 starts with a digit
		if (!isNaN(parseInt(a[0][0])) || !isNaN(parseInt(b[0][0]))) {
			console.log("numbers!");
			if (sortVector == 1) {
				if (parseFloat(tempA) > parseFloat(tempB)) {
					console.log("'" + tempA + "' > '" + tempB + "'");
				   	return true;
				}
				else {
					console.log("'" + tempA + "' < '" + tempB + "'");
				   	return false;
				}	
			}
			else {
				if (parseFloat(tempA) < parseFloat(tempB)) {
					console.log("'" + tempA + "' > '" + tempB + "'");
				   	return true;
				}
				else {
					console.log("'" + tempA + "' < '" + tempB + "'");
				   	return false;
				}
			}	
		}
		// or else sort alphabetically
		else {
			if (sortVector == 1) {
				return(a[0] > b[0]);
			}
			else {
				return(a[0] < b[0]);
			}
		}

    });

	// append back the items, in sorted way
	var compareTable = $("#compare .table");
	for (var i = 0; i < indexVals.length; i++) {
		compareTable.append(rows[indexVals[i][1]]);
	};



	if (sortVector == 1) {
		console.log("sort UP");
	}
	else if (sortVector == 2) {
		console.log("sort DOWN");
	};
}


// remove links

$("#compare .remove-link").click(function(e){
	e.preventDefault();
	$(this).parent().parent().remove();
});