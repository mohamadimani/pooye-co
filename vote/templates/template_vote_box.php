<?php

function vote_temp($post_vote_question = '', $post_vote_answer = '')
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

    .vote_table tr:nth-child(even)  {
        background-color: #f8faff;
    }

    .question {
        background-color: #eaeaea;
    }
</style>

<div class="metabox_inside vote_table_row">
    <form method="post" action="">
        <table class="form-table ">
                <tr class="question" >
                    <td>
                        <label>' . $post_vote_question . '</label>
                    </td>
                </tr>
                <tbody class="vote_table">';
        $text='';
    if (!empty($post_vote_answer[0])) {
        foreach ($post_vote_answer as $key => $answer) {

            $text .= '<tr>
                            <td>
                                <input type="radio" name="user_vote_answer" id="vote_answer' . $key . '"
                                       value="row_' . $key . '">
                                <label for="vote_answer' . $key . '">' . $answer . '  </label>
                            </td>
                        </tr>';
        }
    }
    $text .= '<tr>
                    <td>
                        <button type="submit">ثبت رای</button>
                    </td>
                </tr>
                </tbody>
        </table>
    </form>
</div>';

    return $text;
}

