import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { UrlHtmlRendererModule } from '@spryker/html-renderer';
import { HtmlRendererUrlComponent } from './html-renderer-url.component';

@NgModule({
    imports: [CommonModule, UrlHtmlRendererModule],
    declarations: [HtmlRendererUrlComponent],
    exports: [HtmlRendererUrlComponent],
})
export class HtmlRendererUrlModule {
}
