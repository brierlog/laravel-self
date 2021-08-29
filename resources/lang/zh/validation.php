<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute 必须接受.',
    'active_url' => ':attribute 不是一个有效的 URL.',
    'after' => ':attribute 日期必须在 :date 之后.',
    'after_or_equal' => ':attribute 日期必须在 :date 之后或相等.',
    'alpha' => ':attribute 只能包含字母.',
    'alpha_dash' => ':attribute 只能包含 字母，数字和下划线.',
    'alpha_num' => ':attribute 只能包含 字母，数字.',
    'array' => ':attribute 只能是数组.',
    'before' => ':attribute 必须在 :date 之前.',
    'before_or_equal' => ':attribute 必须在 :date 之前或相等.',
    'between' => [
        'numeric' => ':attribute 必须在 :min 到 :max 之间.',
        'file' => ':attribute 字节长度必须在 :min 到 :max 之间.',
        'string' => ':attribute 字符长度必须在 :min 到 :max 之间.',
        'array' => ':attribute 数量必须在 :min 到 :max 之间.',
    ],
    'boolean' => ':attribute 必须是 0 或 1.',
    'confirmed' => '二次输入:attribute 不一样.',
    'date' => ':attribute 不是一个有效的日期.',
    'date_format' => ':attribute 与 :format 格式不匹配.',
    'different' => ':attribute 和 :other 必须不一样.',
    'digits' => ':attribute 必须是 :digits 位数字.',
    'digits_between' => ':attribute 数字长度必须在 :min 到 :max 之间.',
    'dimensions' => ':attribute 图片尺寸不合法.',
    'distinct' => ':attribute 包含重复的值.',
    'email' => ':attribute 必须是一个有效的邮箱地址.',
    'exists' => ':attribute 不是有效的值.',
    'file' => ':attribute 必须是一个文件.',
    'filled' => ':attribute 必填.',
    'image' => ':attribute 必须是一张图片.',
    'in' => ':attribute 不是有效的值.',
    'in_array' => ':attribute 在 :other 中不存在.',
    'integer' => ':attribute 必须是整数.',
    'ip' => ':attribute 必须是一个有效的 IP 地址.',
    'json' => ':attribute 必须是有个有效的 JSON 字符串.',
    'max' => [
        'numeric' => ':attribute 不能大于 :max.',
        'file' => ':attribute 不能大于 :max kilobytes.',
        'string' => ':attribute 不能大于 :max 个字符.',
        'array' => ':attribute 的最大个数为 :max 个.',
    ],
    'mimes' => ':attribute 仅允许以下文件 MIME: :values.',
    'mimetypes' => ':attribute 仅允许以下文件类型: :values.',
    'min' => [
        'numeric' => ':attribute 不能小于 :min.',
        'file' => ':attribute 至少必须 :min 字节.',
        'string' => ':attribute 至少必须 :min 字符.',
        'array' => ':attribute 至少必须 :min 个.',
    ],
    'not_in' => ':attribute 无效.',
    'numeric' => ':attribute 必须是数字.',
    'present' => ':attribute 不能为空.',
    'regex' => ':attribute 格式无效.',
    'required' => ':attribute 必填.',
    'required_if' => '当 :other 是 :value 时， :attribute 字段必填.',
    'required_unless' => ':attribute 字段是必须的，除非 :other 是在 :values 中.',
    'required_with' => 'The :attribute field is required when :values is present.',
    'required_with_all' => 'The :attribute field is required when :values is present.',
    'required_without' => '当 :values 不存在.:attribute 字段必填.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same' => ':attribute 和 :other 必须一致.',
    'size' => [
        'numeric' => ':attribute 必须是 :size.',
        'file' => ':attribute 必须是 :size 字节.',
        'string' => ':attribute 必须是 :size 字符.',
        'array' => ':attribute 必须包含 :size 个.',
    ],
    'string' => ':attribute 必须是字符串.',
    'timezone' => ':attribute 必须是有效的时区.',
    'unique' => ':attribute 已经存在.',
    'uploaded' => ':attribute 上传失败.',
    'url' => ':attribute 不有效的 url.',

    // Extend
    'db_object' => ':attribute 不存在或无权操作.',
    'script_sc_id' => 'required scId in script content',
    'param_name' => 'required param name in param content',
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

    'up_line_check' => '推荐人信息填写错误.',
    'tin_code_check' => '证件信息校验错误.',
    'captcha' => '请输入正确的验证码.',
    'carrier_content' => '电子发票信息有误',
    'identity' => '发票统一编号有误',
    'telephone' => ':attribute 格式有误',
    'id_card' => ':attribute 格式有误',
];
