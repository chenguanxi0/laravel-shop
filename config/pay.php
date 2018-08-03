<?php

return [
    'alipay' => [
        'app_id'         => '2016091800541188',
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAv5a994sZEJVCFDhGdneicCuKWsVooDQgG05mKSBMyd4otJV3qhG/KNfkb3sREdKDoY+EKZszyJpm65sdmC5vyGby/f8AZ8sMmq1VpkepGIwnpeAASK6H21jfC0bhi6bQBd8wRQ4xlRe787eaxN1OdRzQfhQRXcyuwQRg7ciby/mtFM0ZzHX8KU6uyLlFxMudsgFTL4L2ATamu6KOXbIOfcRjCdOVvU10LtZ47/3cB3K1R2xhwvixYVOUqdBRc04L6ehZ/D9RvnOGMlVcW/dXEbR0sdB3iu3nHR9hRkWYHY3NDqHNLJrrrdk6bQEhu0ht4Vm3/xEDKZpsy589GbWVjwIDAQAB',
        'private_key'    => 'MIIEowIBAAKCAQEAovPPWpE2XEjIcAq57Y2cJkPWg79sbBODtQICClkwKZoeVDG/fQKnA8iu6EWn4f0LAEJDvuf6cB69DeQXGVx2cWSzvCdWtQq9QakmWPjvEmNHtu8giQ/dLSI8s3fxsOFW9K6Bp8Fta8rtcKx77Z6g1HTdXk0LKN8FD7mEf/GQIYOwgL8vwZd+g5LHg3opl/XLWPmaeDSL3aQleKnplMG3eGmz6uZlP8Yg0pAnJapU5Y9Or7fULErPsnKxMiMPEMcHcDiADeIj0214VlQVBwPBK/wtim18yq1KoD0WXM9npjfBYwSnQ/Xrc+UoRVxxkpRs/0o4Sm30ejVJOGrZ/s3qgQIDAQABAoIBABFj15MgwB5lHg7m9iSl4i4rOy9gNF/sTTf5+OHF1t/5tJbjoD3lFJHQzum21U3GxW/HopczBoUEosRivRxVw8YiKxQ/Vn+Hn97sAE2qc0kRE0wwhNZbvQzeBIBaoo5v3enK3cdbWyQPyfsmTSt0rFFAD4fKzRAVjOK6t6s3ylvpGECqr7dl+4F9OGu99mE3EIY04BOko/nkc4z/EYsqHxKkYuje+OSRB38gjQq6rRbPoDTFbGYV0hMkgG6uRpq3hc8SIG+X01LnswICHPauAPo7eRcWj8CISyFbvuG30QzqSc2tXetonoyppUGHvP6aHQOsSPeuaUBYp4dO2r6auukCgYEA1TaVGEbE+dNdMniJLwNOjru/Ow1IiJdv/T/11hfSP5/w9ipg7Oh43UGSqRGPPSzVMFi+N09jM8DSeTFt0FeArQHC4ADBDTRWosEwh5YBetFDcj9YDh+8uZeiBq3SIGYM0uNnlGOlVzqa4+U3H4NhWRWbgZzLsk6Zig75dZu1LusCgYEAw6cso8/TN59FsNNdPtahwYJPchDtDTXvuvIcTRehkZYFT/iHKZazYikcFhRosn88BW9wdLDmbpglwudaWY3JOcKTN+PU68T45XqY8FAtZ8z6pDaGk9Duh3TYQJrCUdaz334bS4JMjeGVKM6mXOGgBZKFLryXK5hlNo/FNevdKUMCgYBCvseFxgG7w3MVba5kTUC2ZgfDULE4MVAZk4A9+U8UI9mm9jVJdK0BdpmW26xflj1W2TES14NSnDZHQjYCVqIthrOetB27KHtGj/vFNixYalIW3cWPBlhqMRHDzX5OLiQtkIrIZTlJSUtbtFHBR7udy6nWtWL++qQOGAn3yzq46QKBgCo/A7WPX3UoL/0hdoXmBGFrSMpBe14Z4lzEgHXo/1sXQKhm6pCBWNR+OfFOkNjR/lzSFj8sH2WL9sNh/zFNG0EckXtE96m9C11JnpJtTZCXQxuIJoDuYULgaP1yLTlMmeRNL4mbZfpQ1ktMa8GsekgZviY8VIv2SQyB+LxO5J9PAoGBALzseDvVnP/Rq+fXe5OWPTH3llo20R+GlpoZ8OgJaKnzWW4i3mSiVDcw882ELorrrPel6zNQ14APSg+IB4PTJIWKekHcvr0ReU4Hjx5aDpGsZRH8vtJ/pRmSNBS6EJ2GHOK4ayYbxaD0j4A75n1yeEUcFR7ZwPZf2qeyiUQK5VdS',
        'log'            => ['file' => storage_path('logs/alipay.log'),
        ],
    ],
    'wechat' => [
        'app_id'      => '',
        'mch_id'      => '',
        'key'         => '',
        'cert_client' => '',
        'cert_key'    => '',
        'log'         => [
            'file' => storage_path('logs/wechat_pay.log'),
        ],
    ],
];