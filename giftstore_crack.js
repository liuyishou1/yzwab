let url = $request.url;
let body = $response.body;

try {
    let obj = JSON.parse(body);

    // 1. 修改 SVIP 权限与去水印
    if (url.includes("/api/app/watermark-policy")) {
        if (obj.user) {
            obj.user.is_svip = 1; 
            obj.user.role = 1; 
            obj.user.svip_expire_at = "2099-12-31T23:59:59.000Z"; 
            obj.user.svip_card_type = "yearly"; 
        }
        if (obj.hasOwnProperty("show_watermark")) {
            obj.show_watermark = false; 
        }
    }

    // 2. 修改解析次数限制为 999 (合并了额度查询和实际解析接口)
    if (url.includes("/api/user/parse-quota/today") || url.includes("/api/v1/parse/")) {
        if (obj.quota) {
            obj.quota.limit = 999; 
            obj.quota.remaining = 999; 
            obj.quota.unlimited = true; 
        }
    }

    body = JSON.stringify(obj);
} catch (e) {
    console.log("解析出错: " + e);
}

$done({body});
