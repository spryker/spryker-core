import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';
import { ToJson } from '@spryker/utils';

export interface Image {
    src: string;
    alt?: string;
}

@Component({
    selector: 'mp-image-sets',
    templateUrl: './image-sets.component.html',
    styleUrls: ['./image-sets.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: {
        class: 'mp-image-sets',
    },
})
export class ImageSetsComponent {
    @Input() @ToJson() images: Image[];
}
