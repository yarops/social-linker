# Social Linker

WordPress plugin for displaying social media links in a floating box.

## Demo

URL: https://phobicshake.s6-tastewp.com/

Admin URL: https://phobicshake.s6-tastewp.com/wp-admin

Username: yarops

Password: FdVITwj3OyE

## Features

- Floating box with social media links
- Ready-to-use icons (VK, Facebook, Instagram, WhatsApp, Telegram)
- Add your own social networks and icons
- Position selection (left/right)
- Responsive design

## Requirements

- WordPress 5.0+
- PHP 7.4+
- Composer
- Node.js 16+ and pnpm/npm/yarn (for development)

## Installation

1. Upload the `social-linker` folder to `/wp-content/plugins/`
2. Run `composer install`
3. For asset building: `pnpm install && pnpm run build`
4. Activate the plugin through the 'Plugins' menu

## Usage

Configure the plugin in the "Social Linker" admin panel:
- Enable/disable the plugin
- Select display position
- Configure links and icons

### Icons

The plugin loads SVG icons from the following sources (in order of priority):
1. Active theme directory: `/wp-content/themes/your-theme/assets/icons/{social_id}.svg`
2. Plugin directory: `/wp-content/plugins/social-linker/assets/icons/{social_id}.svg`
3. Default icon: `link.svg` (if icon not found)

## Development

```bash
# Install dependencies
composer install
pnpm install

# Development mode
pnpm run dev

# Build
pnpm run build
```

### Project Structure

```
social-linker/
├── app/                  # PHP classes
├── src/                  # Source files (TypeScript, SCSS)
├── templates/            # Templates
├── vendor/               # Composer dependencies
└── assets/               # Compiled assets
```

## License

GPL v2 or later