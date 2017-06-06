<?php

function createReturn($status = true, $data = [], $msg = "") {
    return [
        'status' => $status,
        'data' => $data,
        'msg' => $msg
    ];
}