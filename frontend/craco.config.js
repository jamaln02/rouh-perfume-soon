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

  // إعدادات Webpack
  webpack: {
    alias: {
      '@': path.resolve(__dirname, 'src'),
    },

    configure: (webpackConfig) => {
      // تحسين أداء Watch Mode (تجاهل مجلدات غير ضرورية)
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