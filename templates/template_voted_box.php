<?php
function voted_temp($post_vote_question='',$post_vote_answer='',$all_answer_count='',$answer_val='')
{
    $text = '<style>
    .vote_table_row {
        width: 50%;
        display: block;
        /*float: right;*/
        direction: rtl;
        border-radius: 3px;
        border: 1px solid silver;
        margin: 15px auto;
        box-shadow: 1px 1px 3px silver;
    }

    .vote_table tr td span {
        float: left;
        margin: 6px 0;
    }

    .vote_table tr td label {
        display: inline-block;
    }

    table.form-table {
        margin-bottom: 0;
    }

    table.form-table tr td {
        padding: 5px 15px !important;
    }

    .vote_table tr td {
        line-height: 15px;
    }

    .vote_table tr:nth-child(even) {
        background-color: #f8faff;
    }

    .question {
        background-color: #eaeaea;
    }
</style>

<div class="wrap vote_table_row">
    <table class="form-table ">
        <tr class="question">
            <td><label>' . $post_vote_question . '</label></td>
        </tr>
        <tbody class="vote_table">';

    if (!empty($post_vote_answer[0])) {
        foreach ($post_vote_answer as $key => $answer) {
            if (!empty(trim($all_answer_count->answer_count))) {
                $anser_d = $all_answer_count->answer_count;
            } else {
                $anser_d = 1;
            }

            $text .= '<tr>
                    <td>
                        <label for="vote_answer' . $key . '">' . $answer . ' : </label>
                        <span style="float: left">' . round(($answer_val[$key] * 100) / $anser_d, 1) . "%" . '</span>
                    </td>
                </tr>';
        }
    }
    $text .= '</tbody>
    </table>
</div>';
    return $text;
}


