import { ChangeDetectionStrategy, Component, ViewEncapsulation, Input } from '@angular/core';

@Component({
    selector: 'mp-html-renderer-url',
    templateUrl: './html-renderer-url.component.html',
    styleUrls: ['./html-renderer-url.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: {
        class: 'mp-html-renderer-url',
    },
})
export class HtmlRendererUrlComponent {
    @Input() url: string;
}
