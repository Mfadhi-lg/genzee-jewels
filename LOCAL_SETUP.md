# Local WordPress setup

The project packages `genzee_home_v16_hero_fixed.html` as a local WordPress theme. The original storefront interaction and visual system remain intact, while WordPress supplies the CMS and admin foundation.

## Run locally

```bash
docker compose up -d
```

Open [http://localhost:8080](http://localhost:8080) after the containers finish starting. The first start automatically installs WordPress and activates the Genzee theme.

WordPress admin: [http://localhost:8080/wp-admin](http://localhost:8080/wp-admin)

- Username: `admin`
- Password: `admin`

Change the administrator password before exposing any deployment beyond local development.

## Stop or reset

```bash
docker compose down
```

To remove the local WordPress database as well, run `docker compose down -v`. This deletes only the Docker volumes for this project; the prototype HTML and theme files remain on disk.

## Theme structure

- `wp-content/themes/genzee-prototype/` – WordPress theme wrapper.
- `genzee_home_v16_hero_fixed.html` – source prototype, mounted read-only into the theme at runtime.

The theme extracts the prototype’s CSS into `wp_head()` and renders the body as the WordPress home page. This deliberately preserves the working product, cart, wishlist, account, and checkout prototype interactions. For a production commerce launch, connect the product data and checkout to WooCommerce or the preferred commerce API before accepting real orders.
