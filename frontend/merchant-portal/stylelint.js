const stylelint = require('stylelint');
const commandLineParser = require('commander');
const path = require('path');

commandLineParser
    .option('-f, --fix', 'execute stylelint in the fix mode.')
    .option('-p, --file-path <path>', 'execute stylelint only for this file.')
    .parse(process.argv);

const root = path.resolve(__dirname, '../..');
const defaultFilePaths = [`${root}/Bundles/*/src/Spryker/Zed/*/Presentation/Components/**/*.less`];
const filePaths = commandLineParser.filePath ? [commandLineParser.filePath] : defaultFilePaths;

stylelint
    .lint({
        configFile: `${root}/.stylelintrc.mp.js`,
        files: filePaths,
        syntax: 'less',
        formatter: 'string',
        fix: !!commandLineParser.fix,
    })
    .then(function (data) {
        if (data.errored) {
            const messages = JSON.parse(JSON.stringify(data.output));

            process.stdout.write(messages);
            process.exit(1);
        }
    })
    .catch(function (error) {
        console.error(error.stack);
        process.exit(1);
    });
