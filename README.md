# API url:

[GET] http://localhost/index.php?controller=(controller)

[GET] http://localhost/index.php?controller=(controller)&key=(key)

# Example:
http://localhost:8888/awd/index.php?controller=district

return all District

http://localhost:8888/awd/index.php?controller=district&key=2

Return the specific district


# Controller List:
- Import
- District
- Bank
- Branch


# Output
## Single result
```
{
    "header": {
        "success": true,
        "err_code": "0000",
        "err_msg": "No error found",
        "result": [
            {
                "district_key": 2,
                "district_en": "Yau Tsim Mong",
                "district_tc": null,
                "district_sc": null
            }
        ]
    }
}
```

## Multiple result
```
{
    "header": {
        "success": true,
        "err_code": "0000",
        "err_msg": "No error found",
        "result": [
            {
                "bank_key": "1",
                "bank_name_en": "Industrial and Commercial Bank of China (Asia) Limited",
                "bank_name_tc": null,
                "bank_name_sc": null
            },
            {
                "bank_key": "2",
                "bank_name_en": "Bank of  Communications (Hong Kong) Limited",
                "bank_name_tc": null,
                "bank_name_sc": null
            },
            {
                "bank_key": "3",
                "bank_name_en": "Standard Chartered Bank (Hong Kong) Limited",
                "bank_name_tc": null,
                "bank_name_sc": null
            },
            {
                "bank_key": "4",
                "bank_name_en": "Hang Seng Bank Limited",
                "bank_name_tc": null,
                "bank_name_sc": null
            },
            {
                "bank_key": "5",
                "bank_name_en": "The Hongkong and Shanghai Banking Corporation Limited",
                "bank_name_tc": null,
                "bank_name_sc": null
            },
            .....
        ]
    }
}
```
