export default {
    displayName: 'merchant-portal-vendor',
    preset: './jest.preset.js',
    setupFilesAfterEnv: ['<rootDir>/test-setup.ts'],
    roots: ['<rootDir>/../../Bundles'],
    testMatch: ['**/+(*.)+(spec|test).+(ts|js)?(x)'],
    resolver: '@nx/jest/plugins/resolver',
    moduleFileExtensions: ['ts', 'js', 'html'],
    passWithNoTests: true,
    globals: {
        'ts-jest': {
            tsconfig: '<rootDir>/tsconfig.spec.json',
            stringifyContentPathRegex: '\\.(html|svg)$',
        },
    },
};
