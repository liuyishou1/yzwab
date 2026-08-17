let url = $request.url;
let body = $response.body;

try {
    let obj = JSON.parse(body);

    // 1. 修改 SVIP 权限与去水印
    if (url.includes("/api/app/watermark-policy")) {
        if (obj.user) {
            obj.user.is_svip = 1; // 开启 SVIP[span_3](start_span)[span_3](end_span)
            obj.user.role = 1; // 提升角色权限[span_4](start_span)[span_4](end_span)
            obj.user.svip_expire_at = "2099-12-31T23:59:59.000Z"; // 设置 SVIP 到期时间[span_5](start_span)[span_5](end_span)
            obj.user.svip_card_type = "yearly"; // 设置卡类型为年卡[span_6](start_span)[span_6](end_span)
        }
        // 顺便强制关闭水印显示[span_7](start_span)[span_7](end_span)
        if (obj.hasOwnProperty("show_watermark")) {
            obj.show_watermark = false; 
        }
    }

    // 2. 修改解析次数限制为 999
    if (url.includes("/api/user/parse-quota/today")) {
        if (obj.quota) {
            obj.quota.limit = 999; // 将总限制修改为 999[span_8](start_span)[span_8](end_span)
            obj.quota.remaining = 999; // 将剩余次数修改为 999[span_9](start_span)[span_9](end_span)
            obj.quota.unlimited = true; // 开启无限制标志[span_10](start_span)[span_10](end_span)
        }
    }

    body = JSON.stringify(obj);
} catch (e) {
    console.log("解析出错: " + e);
}

$done({body});
