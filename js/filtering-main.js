window.onload = function () {
    var filterContainer = document.getElementsByClassName('filter-container');
    if (filterContainer.length > 0) {
        filterizr = new Filterizr('.filter-container');
    }
}