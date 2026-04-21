// craco.config.js
const path = require("path");

module.exports = {
  // إعدادات ESLint
  eslint: {
    configure: {
      extends: ["plugin:react-hooks/recommended"],
      rules: {
        "react-hooks/rules-of-hooks": "error",
        "react-hooks/exhaustive-deps": "warn",
      },
    },
  },

  //  Webpack settings
  webpack: {
    alias: {
      '@': path.resolve(__dirname, 'src'),
    },

    configure: (webpackConfig) => {
      //   Watch Mode with Ignored Directories to Improve Performance 
      webpackConfig.watchOptions = {
        ...webpackConfig.watchOptions,
        ignored: [
          '**/node_modules/**',
          '**/.git/**',
          '**/build/**',
          '**/dist/**',
          '**/coverage/**',
          '**/public/**',
        ],
      };

      return webpackConfig;
    },
  },
};