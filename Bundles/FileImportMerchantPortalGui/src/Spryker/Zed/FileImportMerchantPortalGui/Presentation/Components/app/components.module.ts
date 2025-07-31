import { NgModule } from '@angular/core';
import { WebComponentsModule } from '@spryker/web-components';
import { ButtonActionComponent, ButtonActionModule } from '@spryker/button.action';
import { CardComponent, CardModule } from '@spryker/card';
import { IconModule } from '@spryker/icon';

import { IconFileImportModule, IconDownloadModule, IconUploadModule } from '../icons';
import { FileImportsComponent } from './file-imports/file-imports.component';
import { FileImportsModule } from './file-imports/file-imports.module';
import { FileImportsTableComponent } from './file-imports-table/file-imports-table.component';
import { FileImportsTableModule } from './file-imports-table/file-imports-table.module';
import { FileImportComponent } from './file-import/file-import.component';
import { FileImportModule } from './file-import/file-import.module';

@NgModule({
    imports: [
        WebComponentsModule.withComponents([
            FileImportsComponent,
            FileImportsTableComponent,
            FileImportComponent,
            ButtonActionComponent,
            CardComponent,
        ]),
        ButtonActionModule,
        FileImportsModule,
        FileImportsTableModule,
        FileImportModule,
        CardModule,
        IconModule,
        IconFileImportModule,
        IconDownloadModule,
        IconUploadModule,
    ],
})
export class ComponentsModule {}
