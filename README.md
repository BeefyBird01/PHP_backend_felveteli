# PHP_backend_felveteli
Keszte Márton felvételi feladata
# Dokumentáció
## Routing

* `GET` `/users` felhasználók lekérdezése
* `POST` `/users` felhasználó hozzáadása
  
    ```
    {
      "first_name": "József",
      "last_name": "Uborka",
      "email_address": "ubijozsi@example.org",
      "phone_number": "+36203114566",
      "password": "123456"           //must be at least 6 characters long
    }
    ```
* `GET` `/parcels/{parcel_number}` csomag lekérdezése
* `POST` `/parcels` csomag hozzáadása
  
    ```
    {
      "user_id": "3",
      "size": "M"      //both upper and lowercase letters are accepted
    }
    ```

## Futtatáshoz szükséges
* composer install
* A root a public mappa, entry point az index.php file
* Az src mappán belül található config.php file-ban állíthatóak az adatbázis kapcsolódáshoz szükséges adatok.
