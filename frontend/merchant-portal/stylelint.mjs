import commandLineParser from 'commander';
import path from 'path';
import stylelint from 'stylelint';
import { fileURLToPath } from 'url';

commandLineParser
    .option('-f, --fix', 'execute stylelint in the fix mode.')
    .option('-p, --file-path <path>', 'execute stylelint only for this file.')
    .parse(process.argv);

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const root = path.resolve(__dirname, '../..');
const defaultFilePaths = [`${root}/Bundles/*/src/Spryker/Zed/*/Presentation/Components/**/*.less`];
const filePaths = commandLineParser.filePath ? [commandLineParser.filePath] : defaultFilePaths;

stylelint
    .lint({
        configFile: `${root}/.stylelintrc.mp.js`,
        files: filePaths,
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
