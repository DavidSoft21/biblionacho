import { ComponentFixture, TestBed } from '@angular/core/testing';

import { LendbookShowComponent } from './lendbook-show.component';

describe('LendbookShowComponent', () => {
  let component: LendbookShowComponent;
  let fixture: ComponentFixture<LendbookShowComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [LendbookShowComponent]
    });
    fixture = TestBed.createComponent(LendbookShowComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
