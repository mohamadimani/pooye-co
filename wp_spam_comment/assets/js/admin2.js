$(document).ready(function () {
    $('a.add_row').click(function () {
        var last_row_number = $('tbody.vote_table ').find('input[type=text]').length + 1
        var input = '<input type="text" name="vote_answer[]" id="vote_answer' + last_row_number + '"> <span onclick="remove_row(this)" class="remove_row">-</span> ';
        var label = '<label for="vote_answer' + last_row_number + '"> پاسخ   </label> ';
        var row = '<tr> <th scope="row">' + label + '</th><td>' + input + '</td></tr>';
        $('tbody.vote_table ').append(row);
    });

});

function remove_row(item) {
    var last_row_number = $('tbody.vote_table').find('input[type=text]').length
    if (last_row_number > 1) {
        $(item).parents('tr').remove()
    }
}