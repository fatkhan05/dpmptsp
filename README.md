## Daftar Isi
- [Documentation API](#doc-api)
    - [Login](#login)
    - [Get Profile Pegawai](#get-profile-pegawai)
    - [Get Pekerjaan Pegawai](#get-pekerjaan-pegawai)
    

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Doc Api
### Service Root: http://192.168.2.111/develop/dpmptsp/public/api/

#### Login
Routes: /login  
method: post  
Required:  
- nomor_pegwai  
- password  

return:
```
{
    "status": "success",
    "code": 200,
    "message": "Selamat Datang ",
    "data": {
        "id": 2,
        "username": "pegawai1",
        "password": "$2y$10$wKEXkdYQIckw95nPhm1FZeYUQFvu5L3Mi3jQSHGg9MNzp2O.qfB9W",
        "level": 2,
        "nomor": "1234",
        "status": "1",
        "token": "pNlnoDQF1nr5Picdedd63cb5502172",
        "created_at": "2022-07-28T10:21:11.000000Z",
        "updated_at": "2022-07-28T10:21:11.000000Z"
    }
}
```
ex:  
nomor_pegawai = 1111  
password = 1234  

#### Get Profile Pegawai
Routes: /profile  
method: post  
Required:
- token  

return:
```
{
    "status": "success",
    "code": 200,
    "message": "Profil Berhasil diambil",
    "data": {
        "id": 2,
        "username": "pegawai1",
        "password": "$2y$10$ocS6zGu2UEvxk/KilM7fdO43YRfIUDwlyWrSVCpxP9yLUN9uhz4e6",
        "level": 2,
        "nomor": "1234",
        "status": "1",
        "token": "7gShCYHAjow0iUS90bdd13dc00549e",
        "created_at": "2022-08-01T08:34:20.000000Z",
        "updated_at": "2022-08-01T08:34:20.000000Z",
        "detail_user": {
            "id": 2,
            "user_id": 2,
            "nama": "Pegawai kebersihan A",
            "nik": "11223344",
            "jenis_kelamin": "Perempuan",
            "tempat_lahir": "Tempat Lahitnya",
            "tgl_lahir": "1999-12-30",
            "telepon": "09876543",
            "tgl_mulai_kerja": "2022-08-01",
            "kategori_pegawai_id": 2,
            "alamat": "alamat nya",
            "shift_id": null,
            "provinsi_id": 11,
            "kabupaten_id": 1101,
            "kecamatan_id": 1101010,
            "desa_id": 1101010001,
            "created_at": "2022-08-01T08:34:25.000000Z",
            "updated_at": "2022-08-01T08:34:25.000000Z",
            "kategori_pegawai": {
                "id": 2,
                "nama": "Kebersihan",
                "kode": "kbr1",
                "created_at": "2022-08-01T08:34:21.000000Z",
                "updated_at": "2022-08-01T08:34:21.000000Z"
            },
            "provinsi": {
                "id": 11,
                "nama": "ACEH",
                "created_at": null,
                "updated_at": null
            },
            "kabupaten": {
                "id": 1101,
                "nama": "KABUPATEN SIMEULUE",
                "provinsi_id": 11,
                "created_at": null,
                "updated_at": null
            },
            "kecamatan": {
                "id": 1101010,
                "nama": "TEUPAH SELATAN",
                "kabupaten_id": 1101,
                "created_at": null,
                "updated_at": null
            },
            "desa": {
                "id": 1101010001,
                "nama": "LATIUNG",
                "kecamatan_id": 1101010,
                "created_at": null,
                "updated_at": null
            },
            "shift": null
        }
    }
}
```

#### Get Pekerjaan Pegawai
Routes: /pekerjaan_pegawai  
method: post  
Required:
- token  
- user_id  

ex:
- user_id = 2  

return:
```
{
    "status": "success",
    "code": 200,
    "message": "Data Pekerjaan Pegawai Berhasil diambil",
    "data": [
        {
            "id": 1,
            "detail_user_id": 2,
            "pekerjaan_id": 1,
            "latitude_sebelum": null,
            "longitude_sebelum": null,
            "foto_sebelum": null,
            "time_take_sebelum": null,
            "latitude_sesudah": null,
            "longitude_sesudah": null,
            "foto_sesudah": null,
            "time_take_sesudah": null,
            "nilai": null,
            "created_at": "2022-08-01T03:00:35.000000Z",
            "updated_at": "2022-08-01T03:00:35.000000Z",
            "detail_user": {
                "id": 2,
                "user_id": 2,
                "nama": "Pegawai kebersihan A",
                "nik": "11223344",
                "jenis_kelamin": "Perempuan",
                "tempat_lahir": "Tempat Lahitnya",
                "tgl_lahir": "1999-12-30",
                "telepon": "09876543",
                "tgl_mulai_kerja": "2022-08-01",
                "kategori_pegawai_id": 2,
                "alamat": "alamat nya",
                "shift_id": null,
                "provinsi_id": 11,
                "kabupaten_id": 1101,
                "kecamatan_id": 1101010,
                "desa_id": 1101010001,
                "created_at": "2022-08-01T03:00:35.000000Z",
                "updated_at": "2022-08-01T03:00:35.000000Z"
            },
            "pekerjaan": {
                "id": 1,
                "nama": "pekerjaan a harus dikerjakan",
                "latitude": "-7.062325",
                "longitude": "112.736237",
                "mulai": "2022-08-03 16:45:00",
                "selesai": "2022-08-04 16:46:00",
                "created_at": "2022-08-01T03:00:35.000000Z",
                "updated_at": "2022-08-01T03:00:35.000000Z"
            }
        },
        {
            "id": 2,
            "detail_user_id": 2,
            "pekerjaan_id": 6,
            "latitude_sebelum": null,
            "longitude_sebelum": null,
            "foto_sebelum": null,
            "time_take_sebelum": null,
            "latitude_sesudah": null,
            "longitude_sesudah": null,
            "foto_sesudah": null,
            "time_take_sesudah": null,
            "nilai": null,
            "created_at": "2022-08-01T03:00:35.000000Z",
            "updated_at": "2022-08-01T03:00:35.000000Z",
            "detail_user": {
                "id": 2,
                "user_id": 2,
                "nama": "Pegawai kebersihan A",
                "nik": "11223344",
                "jenis_kelamin": "Perempuan",
                "tempat_lahir": "Tempat Lahitnya",
                "tgl_lahir": "1999-12-30",
                "telepon": "09876543",
                "tgl_mulai_kerja": "2022-08-01",
                "kategori_pegawai_id": 2,
                "alamat": "alamat nya",
                "shift_id": null,
                "provinsi_id": 11,
                "kabupaten_id": 1101,
                "kecamatan_id": 1101010,
                "desa_id": 1101010001,
                "created_at": "2022-08-01T03:00:35.000000Z",
                "updated_at": "2022-08-01T03:00:35.000000Z"
            },
            "pekerjaan": {
                "id": 6,
                "nama": "pekerjaan f harus dikerjakan",
                "latitude": "-6.980545",
                "longitude": "114.079927",
                "mulai": "2022-08-03 16:45:00",
                "selesai": "2022-08-04 16:46:00",
                "created_at": "2022-08-01T03:00:35.000000Z",
                "updated_at": "2022-08-01T03:00:35.000000Z"
            }
        }
    ]
}
```