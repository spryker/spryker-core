import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { UrlHtmlRendererModule as UrlHtmlRendererModuleCore } from '@spryker/html-renderer';
import { UrlHtmlRendererComponent } from './url-html-renderer.component';

@NgModule({
    imports: [CommonModule, UrlHtmlRendererModuleCore],
    declarations: [UrlHtmlRendererComponent],
    exports: [UrlHtmlRendererComponent],
})
export class UrlHtmlRendererModule {}
