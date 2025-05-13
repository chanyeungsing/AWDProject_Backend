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
